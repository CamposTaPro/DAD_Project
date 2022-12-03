import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'

export const useProductsStore = defineStore('products', () => {
    const axios = inject('axios')

    const products = ref([])

    const totalProducts = computed(() => {
        return products.value.length
    })

    const showProducts = computed(() => {
        return products.value;
    })

    function insertProduct(newProduct) {
        products.value.push(newProduct)
    }

    function deleteProduct(oldProduct){
        const index = products.value.findIndex( prd => prd.id == oldProduct.id)
        if (index >= 0) {
            products.value.splice(index, 1)
        }
    }

    function clearProducts() {
        products.value = []
    }

    async function postProducts() {
        const response = await axios.post('projects', products) //mudar este URL
        clearProducts()
        return response.data.data
    }

    return { products, totalProducts, showProducts, insertProduct, deleteProduct, clearProducts, postProducts }
})