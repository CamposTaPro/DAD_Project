<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useProductsStore } from '@/stores/products.js'

const products = useProductsStore()
const serverBaseUrl = inject("serverBaseUrl");

const deleteProduct = (product) => {
    products.deleteProduct(product)
}

const createOrder = () => {
    products.postProducts()
}

const photoFullUrl = (product) => {
    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}

</script>

<template>
    <h1>Carrinho</h1>
    <h1>{{products.totalProducts}}</h1>
    <ul>
        <li v-for="product in products.showProducts" :key="product.id">
            <div>
                <h2>{{ product.name }}</h2>
                <img :src="photoFullUrl(product)" />
                <!--<p> {{ product.type }}</p>-->
                <p>{{ product.price }}€</p>
            </div>

            <button type="button" class="btn btn-primary" @click="deleteProduct(product)">Retirar produto</button>
        </li>
    </ul>
    <button type="button" class="btn btn-secondary" @click="createOrder">Criar pedido</button>
</template>

<style scoped>

</style>