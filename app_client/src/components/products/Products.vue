<script setup>
import { ref, inject, onMounted, watch, toDisplayString } from 'vue'
import { useProductsStore } from '@/stores/products.js'

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
    toast.success('O '+ product.name +' foi adicionado ao carrinho!');
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

/* CSS */
button{
  align-items: center;
  background-clip: padding-box;
  background-color: #fa6400;
  border: 1px solid transparent;
  border-radius: .25rem;
  box-shadow: rgba(0, 0, 0, 0.02) 0 1px 3px 0;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  display: inline-flex;
  font-family: system-ui,-apple-system,system-ui,"Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 16px;
  font-weight: 600;
  justify-content: center;
  line-height: 1.25;
  margin: 0;
  min-height: 3rem;
  padding: calc(.875rem - 1px) calc(1.5rem - 1px);
  position: relative;
  text-decoration: none;
  transition: all 250ms;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: baseline;
  width: auto;
}

button:hover,
button:focus {
  background-color: #fb8332;
  box-shadow: rgba(0, 0, 0, 0.1) 0 4px 12px;
}

button:hover {
  transform: translateY(-1px);
}

button:active {
  background-color: #c85000;
  box-shadow: rgba(0, 0, 0, .06) 0 2px 4px;
  transform: translateY(0);
}
</style>