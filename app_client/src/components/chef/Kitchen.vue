<script setup>
import { ref, inject, onMounted, watch } from 'vue'

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");

const products = ref([])
const selectedStatus = ref(null)
const status = ref([])
const order_items = ref([])

const fetchOrders = async () => {
    let response = await axios.get('order_item')
    order_items.value = response.data
    console.log(order_items.value);

    if (order_items.value) {
        order_items.value.forEach((item) => {

            if (!status.value.includes(item.status)) {
                status.value.push(item.status)
            }
        })
    }
}

const fetchOrderItemsByStatus = async (status) => {
    let response = await axios.get(`order_item/${status}`)
    order_items.value = response.data
    console.log(products.value)
}

/*
const photoFullUrl = (product) => {
    /*return product.photo_url
        ? `${serverBaseUrl}/storage/products/${product.photo_url}`
        : `${serverBaseUrl}/storage/products/none.png`                                      estava comentado ate aqui



    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}
*/
watch(selectedStatus, (newStatus) => {
    if (newStatus) {
        fetchOrderItemsByStatus(newStatus)
        console.log(newStatus)

    } else {
        fetchOrders()
    }
})

onMounted(() => {
    fetchOrders()
})

</script>

<template>
    <h1>Products:</h1>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
        <label for="search" class="form-label">Filter by Status:</label>
        <select class="form-select" id="status" v-model="selectedStatus">
            <option :value="null">All status</option>
            <option v-for="Onestatus in status" :key="Onestatus.id" :value="Onestatus">
                {{ Onestatus }}
            </option>
        </select>
    </div>

    <ul v-for="item in order_items" :key="item.status" >
        <li  v-if="item.status == 'W'" >
            <div class="card">
                <!--<img src="img_avatar.png" alt="Avatar" style="width:100%">-->
                <div class="container">
                <h4><b>Estado do prato: {{item.status}}</b></h4> 
                <p>{{item.product_id}}--{{}}</p> 
            </div>
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
</style>