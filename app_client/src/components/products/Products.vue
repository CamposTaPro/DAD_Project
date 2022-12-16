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
            <option :value="null">ALL TYPES</option>
            <option v-for="Onetype in types" :key="Onetype.id" :value="Onetype">
                {{ Onetype.toUpperCase() }}
            </option>
            
        </select>
    </div>
    <div class="panel-heading">
        <h3 class="panel-title">Search results for "<span>{{ selectedType ? selectedType.toUpperCase() : 'ALL TYPES' }}</span>"</h3>
    </div>

    <div class="row">
        <div class="col-sm-4"  v-for="product in products" :key="product.id"> 
  <div class="card my-1" >
    <img class="card-img-top" style="height: 18rem;object-fit: cover;" :src="photoFullUrl(product)" />
    <div class="card-body">
                <h5 src="card-title">{{ product.name }}</h5>
                <!--<p> {{ product.type }}</p>-->
                <p src="card-text">{{ product.price }}€</p>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary" @click="addProduct(product)">Adicionar ao carrinho</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Detalhes</button>
</div>

</div>
</div>
</div>


</template>

<style scope>
li{
    display: inline-block;
    width: 50%;
    margin-bottom: 2%;
}
</style>