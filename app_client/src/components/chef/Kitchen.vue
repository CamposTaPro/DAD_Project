<script setup>
import { ref, inject, onMounted, watch } from 'vue'

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");

const products = ref([])
const selectedStatus = ref(null)
const status = ref([])
const order_item = ref([])

const fetchOrders = async () => {
    let response = await axios.get('products/order/items', {
        params: {
            order_status: 'W'
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

const Terminado = (id) => {
    let response = axios.patch(`orderitems/${id}/status`, {
        order_status: 'R'
    })
    console.log(response.data);
}


const photoFullUrl = (product) => {
    
    /*return product.photo_url
        ? `${serverBaseUrl}/storage/products/${product.photo_url}`
        : `${serverBaseUrl}/storage/products/none.png`                                      estava comentado ate aqui
    */


    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}


onMounted(() => {
    fetchOrders()
})

</script>

<template>
    <h1>Products:</h1>
    <ul v-for="item in order_item">
        <li>
            <div class="card">
                <!--<img src="img_avatar.png" alt="Avatar" style="width:100%">-->
                <div class="container">
                <h4><b>{{item.name}}</b></h4> 
                <img class="comida" :src="photoFullUrl(item)" /> 
                </div>
                <button @click="Terminado(item.id)">Prato Terminado</button>
            </div>
        </li>
    </ul>
</template>

<style scope>
li{
    display: inline-block;
    width: 50%;
    margin-bottom: 2%;
}

.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 50%;
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.container {
  padding: 2px 16px;
}

.comida{
  width: 100%;
  height: 100%;
}
</style>