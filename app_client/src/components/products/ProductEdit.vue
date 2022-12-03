<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter, RouterLink, RouterView } from "vue-router"

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");
const router = useRouter();

const name = ref('')
const description = ref('')
const price = ref(0)
const type = ref('')
const photoFile = ref('')
const photoUrl = ref('')

const props = defineProps({
    id: {
        type: Number,
        default: null
    }
})


const fetchProduct = async () => {
    let names = '';

    const response = await axios.get(`product/${props.id}`)
    if (response.status == 200) {
        name.value = response.data[0].name
        description.value = response.data[0].description
        price.value = response.data[0].price
        type.value = response.data[0].type
        photoUrl.value = response.data[0].photo_url
        photoUrl.value = `${serverBaseUrl}/storage/products/${photoUrl.value}`
    }
    console.log(names)
    console.log(response.data)
}

const editProduct = async () => {
    const response = await axios.put(`product/${props.id}`, {
        name: name.value,
        description: description.value,
        price: price.value,
        type: type.value,
        //photo_url: photoUrl.value
    })
    if (response.status == 200){
        console.log("Deu fixe")
        router.push('/producttable')
    }

}

const photoFullUrl = () => {
    return `${serverBaseUrl}/storage/products/${photoUrl.value}`
}

function readFile(event) {
    photoFile.value = event.target.files[0]
}

watch(photoFile, (photoFile) => {
    let fileReader = new FileReader();

    fileReader.readAsDataURL(photoFile);
    fileReader.onload = (e) => {
        photoUrl.value = e.target.result;
    }
})

onMounted(() => {
    fetchProduct()
})

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
                        <input type="file" class="form-control" id="photoUrl" @change="readFile">
                        <img v-show="photoUrl" :src="photoUrl" />
                    </div>
                    <button type="submit" class="btn btn-primary" @click="editProduct">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>