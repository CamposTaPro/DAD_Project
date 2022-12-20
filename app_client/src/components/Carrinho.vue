<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter, RouterLink, RouterView } from "vue-router"
import { useProductsStore } from '@/stores/products.js'
import { useUserStore } from "@/stores/user.js";

const products = useProductsStore()
const router = useRouter();
const userStore = useUserStore();
const serverBaseUrl = inject("serverBaseUrl");
const axios = inject("axios");


const note = ref([]);
const user = ref([])
const pointsInputed = ref(0)


const deleteProduct = (product) => {
    products.deleteProduct(product)
}

const goToPayment = (pointsInputed) => {
    if (products.totalProducts == 0) {
        alert("Não tem produtos no carrinho")
        return;
    }
    if (userStore.userId != -1) {
        if(!validatePoints(pointsInputed)){
            return;
        }
        userStore.insertPoints(pointsInputed)
    }
    router.push({ name: 'Payment' })
}

const photoFullUrl = (product) => {
    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}

const getUser = async () => {
    const response = await axios.get(`users/${userStore.userId}`)
    if (response.status == 200) {
        user.value = response.data.data.customer[0];
    }
}

const validatePoints = (pointsInputed) => {
    let error = true;
    if (pointsInputed < 0) {
        alert("Não pode inserir valores negativos")
        error = false;
    }
    if(pointsInputed==0){
        alert("Nao vai ser aplicado nenhum desconto")
        return true;
    }
    if (pointsInputed > user.value.points) {
        alert("Não tem pontos suficientes")
        error = false;
    }
    if (pointsInputed % 10 != 0) {
        alert("Os descontos usam pontos de 10 em 10")
        error = false;
    }
    return error;
}

onMounted(() => {
    if (userStore.userId != -1) {
        getUser();
    }
})

</script>

<template>
    <h1>Carrinho - {{ products.totalProducts }} product/s</h1>
    <div class="row" v-if="products.totalProducts > 0">
        <div class="col-sm-4" v-for="product, index in products.showProducts" :key="product.id">
            <div class="card my-1">
                <img class="card-img-top" style="height: 18rem;object-fit: cover;" :src="photoFullUrl(product)" />
                <div class="card-body">
                    <h5 src="card-title">{{ product.name }}</h5>
                    <p src="card-text">{{ product.price }}€</p>
                </div>
                <div class="card-footer">
                    <div>
                        <button type="button" class="btn btn-primary" @click="deleteProduct(product)">Retirar
                            produto</button>
                    </div>
                    <div>
                        <label for="price">Notes:</label>
                        <input type="text" class="form-control" placeholder="sem..."
                            @change="products.insertNoteInProduct(product, note, index)" v-model="note[index]">
                    </div>
                </div>
            </div>
        </div>
        <div v-if="userStore.userId != -1">
            <div>
                <p>Pontos Disponiveis: {{ user.points }}</p>
                <label for="points">Quantidade de pontos que deseja usar</label>
                <input type="number" id="points" name="points" min="0" max="99999999" step="10"  v-model="pointsInputed">
            </div>
        </div>
        <div>
            <p>Total: {{ products.getPriceAllProducts() }}€</p>
        </div>
        <div>
            <button type="button" class="btn btn-secondary" @click="goToPayment(pointsInputed)">Criar pedido</button>
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