<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter, RouterLink, RouterView } from "vue-router"
import { useProductsStore } from '@/stores/products.js'

const products = useProductsStore()
const router = useRouter();
const serverBaseUrl = inject("serverBaseUrl");

const note = ref([]);


const deleteProduct = (product) => {
    products.deleteProduct(product)
}

const goToPayment = () => {
    if (products.totalProducts == 0) {
        //TODO: alert
        alert("Não tem produtos no carrinho")
        return;
    }
    router.push({ name: 'Payment' })
    //products.postProducts()
}

const photoFullUrl = (product) => {
    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}


</script>

<template>
    <h1>Carrinho - {{ products.totalProducts }} product/s</h1>
    <div v-if="products.totalProducts > 0">
        <div v-for="product, index in products.showProducts" :key="product.id">
            <div>
                <ul>
                    <li>
                        <div>
                            <h2>{{ product.name }}</h2>
                            <img style="width: 20%;" :src="photoFullUrl(product)" />
                            <p>{{ product.price }}€</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" @click="deleteProduct(product)">Retirar
                                produto</button>
                        </div>
                        <div>
                            <label for="price">Notes:</label>
                            <input type="text" class="form-control" placeholder="sem..."
                                @change="products.insertNoteInProduct(product, note, index)" v-model="note[index]">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div>
            <p>Total: {{ products.getPriceAllProducts() }}</p>
        </div>
        <div>
            <button type="button" class="btn btn-secondary" @click="goToPayment">Criar pedido</button>
        </div>
    </div>
    
    <div v-else>
        <h1>Não tem produtos no carrinho</h1>
    </div>
</template>

<style scoped>
li {
    display: inline-block;
    width: 50%;
    margin-bottom: 2%;
}
</style>