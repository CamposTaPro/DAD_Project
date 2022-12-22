<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter, RouterLink, RouterView } from "vue-router"
import { useProductsStore } from '@/stores/products.js'
import { useUserStore } from "@/stores/user.js";
import axios from 'axios'

const products = useProductsStore()
const router = useRouter();
const userStore = useUserStore();
const toast = inject("toast");

const axiosInjected = inject('axios')

const type = ref('');
const reference = ref('');
const socket = inject('socket')


const fetchUser = async () => {
    try {
        const response = await axiosInjected.get('users/me')
        type.value = response.data.data.customer[0].default_payment_type;
        reference.value = response.data.data.customer[0].default_payment_reference;
    } catch (error) {
        throw error
    }
}

const validateForm = () => {
    if (reference.value == '') {
        toast.error('Reference is required')
        return;
    }
    if (type.value == '') {
        toast.error('Type is required')
        return;
    }
    if (type.value == 'VISA' && isNaN(reference.value)) {
        toast.error("On type 'VISA', Reference must be a number")
        return;
    }
    if (type.value == 'VISA' && reference.value.length != 16) {
        toast.error("On type 'VISA', Reference have 16 numbers")
        return;
    }
    if (type.value == 'VISA' && reference.value[0] == 0) {
        toast.error("On type 'VISA', Reference can't start with '0'")
        return;
    }
    if (type.value == 'PAYPAL' && (!reference.value.includes('@') || !reference.value.includes('.'))) {
        toast.error("On type 'PAYPAL', Reference has to contain the correct format of an email")
        return;
    }
    if (type.value == 'MBWAY' && reference.value[0] == 0) {
        toast.error("On type 'MBWAY', Reference can't start with '0'")
        return;
    }
    if (type.value == 'MBWAY' && isNaN(reference.value)) {
        toast.error("On type 'MBWAY', Reference must be a number")
        return;
    }
    if (type.value == 'MBWAY' && reference.value.length != 9) {
        toast.error("On type 'MBWAY', Reference have 9 numbers")
        return;
    }
}

const postOrderItems = async (orderItem) => {
    const response = await axiosInjected.post("orderitems", orderItem);

    if (response.status == 200) {
        //toast.success("Order created successfully")
    }
}

const createOrder = async () => {
    var userId
    if (userStore.userId == -1) {
        userId = null
    } else {
        userId = userStore.userId
    }

    var ticket_number = await products.getTicket();
    console.log(ticket_number);

    const response = await axiosInjected.post("orders", {
        ticket_number: ticket_number, //VERIFY --(mudei o if dentro da funcao de ticket.value == 99 para ticket.value >= 99 so para ter a certeza )
        status: "P",
        customer_id: userId,
        total_price: products.getPriceAllProducts(),
        total_paid: userStore.user ? products.getPriceAllProducts() - userStore.discount : products.getPriceAllProducts(),
        total_paid_with_points: userStore.user ? userStore.discount : "0.00",
        points_gained: userStore.user ? Math.floor((products.getPriceAllProducts() - userStore.discount) / 10) : 0,
        points_used_to_pay: userStore.user ? userStore.points : 0,
        payment_type: type.value,
        payment_reference: reference.value,
    });
    if (response.status == 200) {

        let order_id = response.data.id;

        for (var i = 0; i < products.totalProducts; i++) {
            var product = products.products[i];

            var order_local_number = i + 1;
            const orderItem = {
                order_id: order_id,
                order_local_number: order_local_number,
                product_id: product.id,
                status: product.type != "hot dish" ? "R" : "W", //VERIFY -- (faz sentido assim qualquer pedido que nao seja hot dish fica logo ready)
                price: product.price,
                preparation_by: null,
                notes: product.note,
            }

            if (orderItem.status == "W") {
                socket.emit('newHotDish', { product: orderItem });
            }

            await postOrderItems(orderItem);
        }
    }
    if (userStore.userId != -1) {
        changePoints();
    }

    let order = response.data;
    order.order_itens = products;
    products.clearProducts();
    socket.emit('newOrder', order);
    //TODO: alert(aqui é preciso??)
    router.push('/')
}

const verifyPayment = async () => {
    var value = products.getPriceAllProducts();

    const response = await axios.post("https://dad-202223-payments-api.vercel.app/api/payments", {
        type: type.value.toLowerCase(),
        reference: reference.value,
        value: Number(value)
    });
    if (response.status == 201) {
        toast.success("Payment verified successfully")
        router.push({ name: "Login" });
        createOrder();
    }
    if (response.status == 422) {
        //TODO: alert - indicar o motivo do erro(falta testar)
        toast.error(`Payment not valid - ${response.data.message}`)
    }
}

const changePoints = async () => {
    const response = axiosInjected.patch(`users/${userStore.userId}/editpoints`, {
        points: Math.floor((products.getPriceAllProducts() - userStore.discount) / 10) - userStore.points
    })
    if (response.status == 200) {
        //TODO: alert ??? nao percebo qual o objetivo deste alert(foi o copilot que escreveu(imagine))
    }
}

onMounted(() => {
    if (products.totalProducts <= 0) {
        router.push('/')
    }
    if (userStore.user) {
        fetchUser();
    }
})

</script>

<template>
    <h1>Payment</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="validateForm">
                    <div class="form-group">
                        <label for="reference">Reference</label>
                        <input type="text" class="form-control" id="reference" v-model="reference">
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" v-model="type">
                            <option value="VISA">VISA</option>
                            <option value="PAYPAL">PAYPAL</option>
                            <option value="MBWAY">MBWAY</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Value: {{ products.getPriceAllProducts() }}</p>
                    </div>
                    <div v-if="userStore.points != 0 && userStore.userId != -1">
                        <!--TODO - arredondar 2 decimas-->
                        <p>Value with discount: {{ products.getPriceAllProducts() - userStore.discount }} </p>
                    </div>
                    <button type="submit" class="btn btn-primary" @click="verifyPayment">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scope>

</style>