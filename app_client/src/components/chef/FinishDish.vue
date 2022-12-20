<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from "../../stores/user.js"

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");
const router = useRouter();
const status = ref([])
const order_item = ref([])
const userStore = useUserStore()
const socket = inject("socket")


const fetchOrders = async () => {
    let response = await axios.get('orderitems_preparationby', {
        params: {
            status: 'P',
            type: "hot dish",
            
            preparation_by: userStore.user.id
        }
    })
    order_item.value = response.data
    console.log("estou aqui")

    if (order_item.value) {
        order_item.value.forEach((item) => {

            if (!status.value.includes(item.status)) {
                status.value.push(item.status)
            }
        })
    }
}

const fetchTodosOrders = async () => {
    let response = await axios.get('orderitems_preparing', {
        params: {
            status: 'P',
            type: "hot dish"
        }
    })
    order_item.value = response.data

    if (order_item.value) {
        order_item.value.forEach((item) => {

            if (!status.value.includes(item.status)) {
                status.value.push(item.status)
            }
        })
    }
}


async function Terminar(order) {
    const response = await axios.patch(`orderitems/${order.id}/status`, {
        status: 'R',
        preparation_by: userStore.user.id
    });
    socket.emit('readyProduct', order)
    router.go()
}


const photoFullUrl = (product) => {

    /*return product.photo_url
        ? `${serverBaseUrl}/storage/products/${product.photo_url}`
        : `${serverBaseUrl}/storage/products/none.png`                                      estava comentado ate aqui
    */
    return `${serverBaseUrl}/storage/products/${product}`
}



onMounted(() => {
    if (userStore.user == null || userStore.user.type == 'C' || userStore.user.type == 'ED') {
        router.push('/')
    }
    if(userStore.user.type == 'EC'){
        fetchOrders()
    }
    if(userStore.user.type == 'EM'){
        fetchTodosOrders()
    }
    

})

</script>

<template>
    <h1>Pratos em Processo:</h1>
    <div class="row">
        <div class="col-sm-4" v-for="item in order_item">
            <div class="card my-1">
                <img class="card-img-top" :src="photoFullUrl(item.product[0].photo_url)" />
                <div class="container">
                    <p><b>{{ item.product[0].name }}</b></p>
                    <p>order:{{ item.order_id }}</p>
                    <p v-if="item.note != null">Nota: {{ item.note }}</p>
                    <p v-else>Nota: Sem nota</p>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" style="float:right;" v-if="userStore.user?.type != 'EM'"
                        @click="Terminar(item)">Prato Pronto</button>
                    <p v-if="userStore.user?.type != 'EC'">Being prepared by: {{ item.preparation_by }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scope>
.card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    transition: 0.3s;
}

img.comida {
    width: 100%;
    height: 200px;
}

ul {
    display: inline-block;
    width: 30%;
}

@media screen and (max-width: 950px) {
    ul {
        display: inline-block;
        width: 50%;
    }
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

/* CSS */
</style>