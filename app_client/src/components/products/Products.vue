<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useProductsStore } from '@/stores/products.js'

const carrinho = useProductsStore()

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");

const products = ref([])
const selectedType = ref(null)
const types = ref([])

const fetchProducts = async () => {
    let response = await axios.get('products')
    products.value = response.data

    if (products.value) {
        products.value.forEach((product) => {

            if (!types.value.includes(product.type)) {
                types.value.push(product.type)
            }
        })
    }
}

const fetchProductsByType = async (type) => {
    let response = await axios.get(`products/${type}`)
    products.value = response.data
    console.log(products.value)
}

watch(selectedType, (newType, oldType) => {
    if (newType) {
        fetchProductsByType(newType)

    } else {
        fetchProducts()
    }
})


const photoFullUrl = (product) => {
    /*return product.photo_url
        ? `${serverBaseUrl}/storage/products/${product.photo_url}`
        : `${serverBaseUrl}/storage/products/none.png`*/
    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}

const addProduct = async (product) => {
    carrinho.insertProduct(product)
}

onMounted(() => {
    fetchProducts()
})

</script>

<template>
    <h1>Products:</h1>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
        <label for="search" class="form-label">Filter by Type:</label>
        <select class="form-select" id="type" v-model="selectedType">
            <option :value="null">All types</option>
            <option v-for="Onetype in types" :key="Onetype.id" :value="Onetype">
                {{ Onetype }}
            </option>
            
        </select>
    </div>
    <div class="panel-heading">
        <h3 class="panel-title">Search results for "<span>{{ selectedType ? selectedType : 'All types' }}</span>"</h3>
    </div>

    <ul>
        <li v-for="product in products" :key="product.id">
            <div>
                <h2>{{ product.name }}</h2>
                <img style="width: 20%;" :src="photoFullUrl(product)" />
                <!--<p> {{ product.type }}</p>-->
                <p>{{ product.price }}€</p>
            </div>

            <button type="button" class="btn btn-primary" @click="addProduct(product)">Adicionar ao carrinho</button>
        </li>
    </ul>
</template>

<style scope>
li{
    display: inline-block;
    width: 50%;
    margin-bottom: 2%;
}
</style>