<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter, RouterLink, RouterView } from "vue-router"
import { useUserStore } from "@/stores/user.js";

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");
const router = useRouter();
const userStore = useUserStore();

const products = ref([])


const fetchProducts = async () => {
    let response = await axios.get('products')
    products.value = response.data
}

const photoFullUrl = (product) => {
    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}

const deleteProduct = async (id) => {
    let response = await axios.delete(`products/${id}`)
    if(response.status == 200){
        fetchProducts()
    }
}

const editProduct = (id) => {
    router.push({ name: 'ProductEdit', params: { id: id } })
}

onMounted(() => {
    if (userStore.user == null || userStore.user.type != 'EM'){
        router.push('/')
    } else{
       fetchProducts()
    }
})
</script>

<template>
<!--Show Products in a table format-->
<div class="container">
    <h1>Products:</h1>
    <router-link to="/product" class="btn btn-primary">Add Product</router-link>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Price</th>
                <th scope="col">Photo</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <th scope="row">{{product.id}}</th>
                <td>{{product.name}}</td>
                <td>{{product.type}}</td>
                <td>{{product.price}}</td>
                <td><img :src="photoFullUrl(product)" alt="product photo" class="img-thumbnail"></td>
                <td><button type="button" class="btn btn-primary" @click="editProduct(product.id)">Edit</button></td>
                <td><button type="button" class="btn btn-danger" @click="deleteProduct(product.id)">Delete</button></td>
            </tr>
            
        </tbody>
    </table>
</div>
</template>

<style scoped>

</style>