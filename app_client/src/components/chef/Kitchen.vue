<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");
const router = useRouter();
const products = ref([])
const selectedStatus = ref(null)
const status = ref([])
const order_item = ref([])

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

async function Terminado(id){
    const response = await axios.patch(`orderitems/${id}/status`, {
        status: 'R'});
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
    <h1>Products:</h1>
    <ul v-for="item in order_item">
            <div class="card">
                <!--<img src="img_avatar.png" alt="Avatar" style="width:100%">-->
                <div class="container">
                    <h4><b>{{ item.product[0].name }}- order:{{item.order_id}}</b></h4>
                    <img class="comida" :src="photoFullUrl(item.product[0].photo_url)" />
                </div>
                <button @click="Terminado(item.id)">Prato Terminado</button>
            </div>
    </ul>
</template>

<style scope>
li {
    width: 50%;
    margin-bottom: 2%;
}

h4 {
    clear: both;
    display: inline-block;
    overflow: hidden;
    white-space: nowrap; 
}

.card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    width: 80%;
    height: 80%;
}

ul {
    display: inline-block;
    width: 33%;
    margin:0%;
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

.container {
    padding: 10% 22%;
}

.comida {
    width: 80%;
    height: 80%;
}
</style>