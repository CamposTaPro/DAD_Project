<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useUserStore } from "../../stores/user.js"
import { useRouter, RouterLink, RouterView } from "vue-router"
import axios from 'axios'

const axiosInjected = inject('axios')
const router = useRouter();

const orders = ref([])
const userStore = useUserStore()
const toast = inject('toast')
const socket = inject('socket')

socket.on('newOrder', (order) => {
    if (orders.value.some((elem) => elem.id != order.id)) {
        fetchOrders()
        toast.success("Nova order com o id " + order.id + "!")
    }

})

const fetchOrders = async () => {
    let response = await axiosInjected.get('order/pending')
    orders.value = response.data
    console.log(orders.value)
}

socket.on('readyProduct', (product) => {
    orders.value.forEach((order) => {
        order.order_itens.forEach((orderItem) => {
            if (orderItem.id == product.id) {
                orderItem.status = 'R'
                toast.success("Produto com o id " + product.id + " esta ready!")
            }
        })
    })
})


onMounted(() => {
    if (userStore.user == null || userStore.user.type == 'C' || userStore.user.type == 'EC') {
        router.push({ name: 'home' })
    }

    fetchOrders()
})

const CancelarOrder = async (order) => {

    const response = await axiosInjected.patch(`orders/${order.id}/status`, {
        status: 'C'
    });

    orders.value = orders.value.filter((elem) => elem.id != order.id)
    toast.error("Order com o id " + order.id + " cancelada!")
    socket.emit('cancelOrder', order, order.customer_id)
    refund(order);
}

const refund = (order) => {
    var value = order.total_price;
    var type = order.payment_type;
    var reference = order.payment_reference;

    axios.post("https://dad-202223-payments-api.vercel.app/api/refunds", {
        type: type.toLowerCase(),
        reference: reference,
        value: Number(value)
    }).then((response) => {
        if (response.status == 201) {
            toast.success("Refund done successfully")
            
        }
    }).catch((error) => {
        toast.error(`Refund not valid - ${error.response.data.message}`)
    })

}

const EntregarOrder = async (order) => {

    if (orders.value.some((elem) => elem.ticket_number == order.ticket_number && elem.id < order.id)) {
        toast.error("Já existe uma order com o mesmo ticket e esta primeiro que essa order!")
        return
    }

    const response = await axiosInjected.patch(`orders/${order.id}/status`, {
        status: 'R'
    });

    //TODO: ver responsta do response -(feito ???)
    console.log(response.data)
    socket.emit('readyOrder', order, order.customer_id)
    socket.emit('readyOrderPublic', order)

    orders.value = orders.value.map((elem) => {
        if (elem.id == order.id) {
            elem.status = 'R'
        }
        return elem
    })

}

const OrderEntregue = async (order) => {

    const response = await axiosInjected.patch(`orders/${order.id}/status`, {
        status: 'D'
    });

    //TODO ver resposta do response-(feito???)
    console.log(response.data)
    socket.emit('deliverOrderPublic', order)

    orders.value = orders.value.filter((elem) => elem.id != order.id)
    toast.success("Order com o id " + order.id + " foi entregue!")

}

</script>

<template>
    <h1>Orders:</h1>

    <div class="row">
        <div v-for="order in orders" :key="order.id">
            <div class="card my-1">
                <div class="card-header">
                    <h5 src="card-title">Order number: {{ order.id }}</h5>
                </div>
                <div class="card-body">
                    <table class="table  table-striped w-100">
                        <thead>
                            <tr>
                                <th scope="col">PRODUTO</th>
                                <th scope="col">ESTADO ATUAL</th>
                                <th scope="col">NOTAS</th>
                                <th scope="col"> </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="orderItens in order.order_itens">
                                <th scope="row">{{ orderItens.product[0].name }} </th>
                                <td v-if="orderItens.status == 'P'"> A preparar </td>
                                <td v-else-if="orderItens.status == 'W'"> A Espera </td>
                                <td v-else-if="orderItens.status == 'R'"> Feito </td>
                                <td v-if="orderItens.notes"> {{ orderItens.notes }} </td>
                                <td v-else>Sem Notas</td>
                                <td else></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--If all ordersItens are Ready then appear a botton-->
                <div class="card-footer">
                    <button v-if="order.order_itens.every(elem => elem.status == 'R') && order.status == 'P'"
                        @click="EntregarOrder(order)" v-show="userStore.user?.type != 'EM'" class="btn btn-primary">Entregar Order!</button>
                    <button v-else-if="order.status == 'R'" @click="OrderEntregue(order)" class="btn btn-success">Order
                        Entregue!</button>
                    <button class="btn btn-danger" @click="CancelarOrder(order)" v-show="userStore.user?.type == 'EM' ">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<style scope>
li {
    display: inline-block;
    width: 50%;
    margin-bottom: 2%;
}
</style>