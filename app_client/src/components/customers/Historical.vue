<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useUserStore } from "@/stores/user.js";
import { useRouter, RouterLink, RouterView } from "vue-router"

//const serverBaseUrl = inject("serverBaseUrl");
const axios = inject("axios");
const userStore = useUserStore();
const router = useRouter();

const orders = ref([]);

const loadOrders = async () => {
    const response = await axios.get(`customer/${userStore.userId}/orders`);

    if (response.status == 200) {
        orders.value = response.data.data;
    } else {
        orders.value = 0;
    }
}

onMounted(()=> {
    if (userStore.userId == -1 || userStore.user.type != "C") {
        router.push({ name: 'home' })
    }

    loadOrders();
})

</script>

<template>
    <h1>Historical:</h1>
        <table class="table table-hover">
      <thead >
        <tr>
          <th scope="col">#</th>
          <th scope="col">Ticket Number</th>
          <th scope="col">Payment Type</th>
          <th scope="col">Total Price</th>
          <th scope="col">Date</th>
          <th scope="col">Deliver</th>
        </tr>
    </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
                <th  scope="row">{{ order.id }}</th>
                <td>{{ order.ticket_number }}</td>
                <td> {{ order.payment_type }}</td>
                <td>{{ order.total_price }}€</td>
                <td>{{ order.date }}</td>
                <td v-if="order.delivered_by">{{order.delivered_by}}</td>
                <td v-else>No Deliver Info</td>
        </tr>
    </tbody>
    </table>
    </template>
    


<style scoped>

</style>