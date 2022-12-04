<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useUserStore } from "@/stores/user.js";

//const serverBaseUrl = inject("serverBaseUrl");
const axios = inject("axios");
const userStore = useUserStore();

const orders = ref([]);

const loadOrders = async () => {
    const response = await axios.get(`customer/${userStore.userId}/orders`);

    if (response.status == 200) {
        orders.value = response.data.data;
    }
}

onMounted(()=> {
    loadOrders();
})

</script>

<template>
<h1>Historical:</h1>
<ul>
    <li v-for="order in orders" :key="order.id">
        <div>
            <h2>{{ order.id }}</h2>
            <p>Ticket Number: {{ order.ticket_number }}</p>
            <p>Status: {{ order.status }}</p>
            <p>Payment Type: {{ order.payment_type }}</p>
            <p>Total Price: {{ order.total_price }}€</p>
            <p>Date: {{ order.date }}</p>
            <p>Delivered by: {{ order.delivered_by }}</p>
        </div>
    </li>
</ul>
</template>

<style scoped>

</style>