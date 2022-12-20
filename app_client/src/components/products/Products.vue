<script setup>
import { ref, inject, onMounted, watch, toDisplayString } from 'vue'
import { useProductsStore } from '@/stores/products.js'
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'
import { useUserStore } from "../../stores/user.js"

//TODO - preciso?????
defineProps({
    title: {
        type: String,
        default: "<<Title goes here>>",
    },
});


// ----------- TODO: apagar???? -------------

const userStore = useUserStore()
const showModal = ref(false);
const ProductModal = ref(false);

const open = (product, index) => {
    showModal.value = true;
    $vfm.show({
        component: VueFinalModal,
        props: {
            product: product[index],
        },
    });
};
const close = () => {
    showModal.value = false;
};
// ----------- TODO: apagar???? -------------


const carrinho = useProductsStore()
const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");

const products = ref([])
const selectedType = ref(null)
const types = ref([])
const toast = inject('toast')


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
    //TODO - verificar se o type é valido
    //TODO - verificar response
    products.value = response.data
}

watch(selectedType, (newType, oldType) => {
    if (newType) {
        fetchProductsByType(newType)

    } else {
        fetchProducts()
    }
})



const photoFullUrl = (product) => {
    return `${serverBaseUrl}/storage/products/${product.photo_url}`
}

const addProduct = async (product) => {
    carrinho.insertProduct(product)
    toast.success('O ' + product.name + ' foi adicionado ao carrinho!');
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
        <h3 class="panel-title">Search results for "<span>{{ selectedType ? selectedType.toUpperCase() : 'ALL TYPES'
        }}</span>"</h3>
    </div>

    <div class="row">
        <div class="col-sm-4" v-for="product, index in products" :key="product.id">
            <div class="card my-1">
                <img class="card-img-top" style="height: 18rem;object-fit: cover;" :src="photoFullUrl(product)" />
                <div class="card-body">
                    <h5 src="card-title">{{ product.name }}</h5>
                    <p src="card-text">{{ product.price }}€</p>
                </div>
                <div class="card-body" v-if="userStore.user != null && userStore.user.type == 'EM'">
                    <p src="card-text">Tipo: {{ product.type }}</p>
                    <p src="card-text">Descricao: {{ product.description }}</p>
                </div>
                <div class="card-footer" style="display:inline;">
                    <button type="button" class="btn btn-primary" @click="addProduct(product)">Adicionar ao
                        carrinho</button>
                    <!--TODO: apagar????-->
                    <!--Create a modal that shows a product selected-->
                    <button type="button" class="btn btn-primary" @click="open(products, index)">Ver
                        detalhes</button>
                    
                    <!--TODO: apagar????-->
                </div>
            </div>
        </div>
    </div>


</template>

<style scope>
.card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    transition: 0.3s;
}

img.comida {
    width: 100%;
    height: 250px;
}

ul {
    display: inline-block;
    width: 15%;
}

@media screen and (max-width: 950px) {
    ul {
        display: inline-block;
        width: 30%;
    }
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

.modal__title {
    margin: 0 2rem 0 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.modal__close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
}
</style>
