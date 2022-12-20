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

console.log(userStore.user.id);

const fetchOrders = async () => {
    let response = await axios.get('orderitems_hotdishes', {
        params: {
            status: 'W',
            type:"hot dish"
        }
    })
    order_item.value = response.data
    console.log(order_item.value);

    if (order_item.value) {
        order_item.value.forEach((item) => {

            if (!status.value.includes(item.status)) {
                status.value.push(item.status)
            }
        })
    }
}

async function Comecar(id){
    const response = await axios.patch(`orderitems/${id}/status`, {
        status: 'P',
        preparation_by: userStore.user.id
    });
    console.log(response.data);
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
    fetchOrders()
})

</script>

<template>
    <h1>Pratos por preparar:</h1>
    <ul v-for="item in order_item">
            <div class="card">
                <img class="comida" :src="photoFullUrl(item.product[0].photo_url)" />
                <div class="container">
                    <b>{{ item.product[0].name }}</b>
                    <p>order:{{item.order_id}}</p>
                    <p v-if="item.note!=null">Nota: {{item.note}}</p>
                    <p v-else>Nota: Sem nota</p>
                </div>
                <button v-if="userStore.user?.type != 'EM'" @click="Comecar(item.id)">Comecar Preparo</button>
            </div>
    </ul>
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
    width: 33%;
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

</style>