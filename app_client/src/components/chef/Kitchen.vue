<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from "../../stores/user.js"

const axios = inject('axios')
const serverBaseUrl = inject("serverBaseUrl");
const router = useRouter();
const status = ref([])
const order_item = ref([])
const userStore = useUserStore()
const socket = inject("socket")

socket.on('newHotDish', (order) => {
    fetchOrders()
})

const fetchOrders = async () => {
    let response = await axios.get('orderitems_hotdishes', {
        params: {
            status: 'W',
            type: "hot dish"
        }
    })
    order_item.value = response.data

    if (order_item.value) {
        order_item.value.forEach((item) => {

            if (!status.value.includes(item.status)) {
                status.value.push(item.status)
            }
        })
    }
}

async function Comecar(id) {
    const response = await axios.patch(`orderitems/${id}/status`, {
        status: 'P',
        preparation_by: userStore.user.id
    });
    socket.emit('preparingHotDish', response.data)
    router.go()
}


const photoFullUrl = (product) => {
    return `${serverBaseUrl}/storage/products/${product}`
}


onMounted(() => {
    if (userStore.user == null || userStore.user.type == 'C' || userStore.user.type == 'ED') {
        router.push('/')
    } else {
        fetchOrders()
    }

})

</script>

<template>
    <h1>Pratos por preparar:</h1>
    <div class="row">
        <div class="col-sm-4" v-for="item in order_item">
            <div class="card my-1">
                <img class="comida" :src="photoFullUrl(item.product[0].photo_url)" />
                <div class="card-body">
                    <b>{{ item.product[0].name }}</b>
                    <p>order:{{ item.order_id }}</p>
                    <p v-if="item.note != null">Nota: {{ item.note }}</p>
                    <p v-else>Nota: Sem nota</p>
                </div>
                <div class="card-footer" v-if="userStore.user?.type != 'EM'">
                    <button type="button" class="btn btn-secondary" @click="Comecar(item.id)">Comecar Preparo</button>
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
    height: 200px;
}

ul {
    display: inline-block;
    width: 33%;
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
</style>