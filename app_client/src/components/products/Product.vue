<script setup>
import { ref, watch, inject } from 'vue'
import { useRouter } from "vue-router"
import axios from 'axios'

const toast = inject("toast")

const router = useRouter()

const name = ref('')
const description = ref('')
const price = ref(0)
const type = ref('')
const photoFile = ref('')
const photoUrl = ref('')
const file = ref(null)


function readFile(event) {
    photoFile.value = file.value.files[0];
}

const createProduct = async () => {
    const productPhoto = file.value?.files[0]
    if (productPhoto) {
        const formData = new FormData()
        formData.append('file', productPhoto)
        formData.append('name', name.value)
        formData.append('type', type.value)
        formData.append('description', description.value)
        formData.append('price', price.value)


        const response = await axios.post('http://server_api.test/api/products', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        if (response.status == 200) {
            toast.success('Product #' + response.data.id + ' was created successfully.')
            router.push({ name: 'Products' })
        } else {
            toast.error('Product was not created due to unknown server error!')
        }
    }

}

watch(photoFile, (photoFile) => {
    let fileReader = new FileReader();

    fileReader.readAsDataURL(photoFile);

    fileReader.onload = (e) => {
        photoUrl.value = e.target.result;
    }
})

const validateForm = () => {
    if (name.value == '') {
        toast.error('Name is required!')
    }
    if (description.value == '') {
        toast.error('Description is required!')
    }
    if (price.value == 0) {
        toast.error('Price is required!')
    }
    if (type.value == '') {
        toast.error('Type is required!')
    }
    if (price.value < 0) {
        toast.error('Price must be greater than 0!')
    }
}

</script>


<template>
    <h1>Product</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="validateForm">
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