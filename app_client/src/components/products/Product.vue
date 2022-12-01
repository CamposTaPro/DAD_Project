<script setup>
import { ref, watch, inject } from 'vue'

const axios = inject('axios')

const name = ref('')
const description = ref('')
const price = ref(0)
const type = ref('')
const photoFile = ref(null)
const photoUrl = ref('')

function readFile(event) {
    photoFile.value = event.target.files[0]
    console.log(photoFile.value)
}

const createProduct = async () => {
    const formData = new FormData()
    formData.append('file', photoFile.value)
    
    const product = {
        name: name.value ? name.value : "x",
        type: type.value ? type.value : "drink",
        description: description.value ? description.value : "x",
        file: formData,
        price: price.value
    }

    const config = {
        headers: { 'content-type': 'multipart/form-data' }
    }

    const response = await axios.post('products', product, config)
    if (response.status == 201) {
        //\toast.success('Product #' + response.data.data.id + ' was created successfully.')
        //router.push({ name: 'Product', params: { id: response.data.data.id } })
        console.log("deu fixe")
    } else {
        //toast.error('Product was not created due to unknown server error!')
        console.log("fodeu")
    }

}

watch(photoFile, (photoFile) => {
    let fileReader = new FileReader();

    fileReader.readAsDataURL(photoFile);

    fileReader.onload = (e) => {
        photoUrl.value = e.target.result;
    }
})

const idk = () => {

}

</script>


<template>
    <h1>Product</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="idk">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" v-model="name">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" v-model="description">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" v-model="price">
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" v-model="type">
                            <option value="drink">drink</option>
                            <option value="dessert">dessert</option>
                            <option value="hot dish">hot dish</option>
                            <option value="cold dish">cold dish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photoUrl">Photo</label>
                        <input type="file" class="form-control" id="photoUrl" ref="file" @change="readFile">
                        <img v-show="photoUrl" :src="photoUrl" />
                    </div>
                    <button type="submit" class="btn btn-primary" @click="createProduct">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>