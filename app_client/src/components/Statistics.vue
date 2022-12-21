<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useUserStore } from "@/stores/user.js";
import { useRouter, RouterLink, RouterView } from "vue-router"

const axios = inject("axios");
const userStore = useUserStore();
const router = useRouter();

const pointsEarnedThisMonth = ref(0);
const ponintsEarnedAllTime = ref(0);
const moneySpentThisMonth = ref(0);
const moneySavedThisMonth = ref(0);
const ordersThisMonth = ref(0);
const newClietnsThisMonth = ref(0);
const newEmployeesThisMonth = ref(0);
const ordersFromThisMonth = ref(0);

const getDaysOfAccount = () => {
    const today = new Date();
    const created_at = new Date(userStore.user.customer[0].created_at);
    const diffTime = Math.abs(today - created_at);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}

const getPointsEarnedThisMonth = async () => {
    const response = await axios.get(`orders/${userStore.user.customer[0].id}/points`);
    pointsEarnedThisMonth.value = response.data;
}

const getAllPointsEarned = async () => {
    const response = await axios.get(`orders/${userStore.user.customer[0].id}/pointstotal`);
    ponintsEarnedAllTime.value = response.data;
}

const getMoneySpentThisMonth = async () => {
    const response = await axios.get(`orders/${userStore.user.customer[0].id}/moneyspentmonth`);
    moneySpentThisMonth.value = response.data;
}

const getMoneySavedThisMonth = async () => {
    const response = await axios.get(`orders/${userStore.user.customer[0].id}/moneysaved`);
    moneySavedThisMonth.value = response.data;
}

const getOrdersThisMonth = async () => {
    const response = await axios.get(`orders/${userStore.user.customer[0].id}/month`);
    ordersThisMonth.value = response.data
}

const getClientsThisMonth = async () => {
    const response = await axios.get(`customers/totalmonth`);
    newClietnsThisMonth.value = response.data
}

const getEmployeesThisMonth = async () => {
    const response = await axios.get(`employees/totalmonth`);
    newEmployeesThisMonth.value = response.data

}

const getOrdersFromThisMonth = async () => {
    const response = await axios.get(`orders/totalmonth`);
    ordersFromThisMonth.value = response.data
}

onMounted(() => {
    if (userStore.user == null || userStore.user.type == 'ED' || userStore.user.type == 'EC') {
        router.push('/');
    }

    userStore.loadUser
    

    if (userStore.user.type == "C") {
        getPointsEarnedThisMonth();
        getAllPointsEarned();
        getMoneySpentThisMonth();
        getMoneySavedThisMonth();
        getOrdersThisMonth();
    }

    if (userStore.user.type == "EM") {
        getClientsThisMonth();
        getEmployeesThisMonth();
        getOrdersFromThisMonth();
    }
});
</script>

<template>
    <div v-if="userStore.user">
        <h1>Estatisticas:</h1>
        <br>
        <div v-if="userStore.user.type == 'C'">
            <h6>Quantos dias tem a conta: {{ getDaysOfAccount() }} dias</h6>
            <h6>Pontos Totais: {{ userStore.user.customer[0].points }} pontos</h6>
            <h6>Pontos ganhos desde sempre: {{ ponintsEarnedAllTime }} pontos</h6>
            <h6>Pontos gastos este mês: {{ pointsEarnedThisMonth }} pontos </h6>
            <h6>Dinheiro gasto este mês: {{ moneySpentThisMonth }}€</h6>
            <h6>Dinheiro salvo com pontos por mês: {{ moneySavedThisMonth }}€</h6>
            <h6>Orders feitas por mês: {{ ordersThisMonth }} orders</h6>
        </div>
        <div v-if="userStore.user.type == 'EM'">
            <h6>Clientes novos este mês: {{ newClietnsThisMonth }} clientes</h6>
            <h6>Empregados novos este mês: {{ newEmployeesThisMonth }} empregados</h6>
            <h6>Orders deste mês: {{ ordersFromThisMonth }} orders</h6>
        </div>
    </div>
</template>

<style scoped>

</style>