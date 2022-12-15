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

console.log(userStore.user.id);

const fetchOrders = async () => {
    let response = await axios.get('orderitems_preparationby', {
        params: {
            status: 'P',
            type:"hot dish",
            preparation_by: userStore.user.id
        }
    })
    order_item.value = response.data
    console.log(order_item.value);

    if (order_item.value) {
        order_item.value.forEach((item) => {

            if (!status.value.includes(item.status)) {
                status.value.push(item.status)
            }
        })
    }
}

async function Terminar(id){
    const response = await axios.patch(`orderitems/${id}/status`, {
        status: 'R',
        preparation_by: userStore.user.id
    });
    console.log(response.data);
    router.go()
}


const photoFullUrl = (product) => {

    /*return product.photo_url
        ? `${serverBaseUrl}/storage/products/${product.photo_url}`
        : `${serverBaseUrl}/storage/products/none.png`                                      estava comentado ate aqui
    */
    return `${serverBaseUrl}/storage/products/${product}`
}


onMounted(() => {
    fetchOrders()
})

</script>

<template>
    <h1>Pratos em Processo:</h1>
    <ul v-for="item in order_item">
            <div class="card">
                <img class="comida" :src="photoFullUrl(item.product[0].photo_url)" />
                <div class="container">
                    <p><b>{{ item.product[0].name }}</b></p>
                    <p>order:{{item.order_id}}</p>
                    <p v-if="item.note!=null">Nota: {{item.note}}</p>
                    <p v-else>Nota: Sem nota</p>
                </div>
                <button @click="Terminar(item.id)">Prato Pronto</button>
            </div>
    </ul>
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
    width: 30%;
}
 
@media screen and (max-width: 950px) {
    ul {
        display: inline-block;
        width: 50%;
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