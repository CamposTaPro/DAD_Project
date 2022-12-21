<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useUserStore } from "../stores/user.js"
import axios from 'axios'

const axiosInjected = inject('axios')

const ordersPreparing = ref([])
const ordersReady = ref([])
const toast = inject('toast')
const socket = inject('socket')

socket.on('newOrder', (order) => {
    if (ordersPreparing.value.some((elem) => elem.id != order.id)) {
        ordersPreparing.value.push(order)
    }
})

socket.on('cancelOrder', (order) => {
    ordersPreparing.value = ordersPreparing.value.filter((elem) => elem.id != order.id)
})

const fetchOrders = async () => {
  try {
    let response = await axiosInjected.get('orders/P')
    if (response.status == 200) {
      ordersPreparing.value = response.data
    }
  } catch (error) {
    //toast.error('Não existem orders em espera de momento')
  }

  try {
    response = await axiosInjected.get('orders/R')
    if (response.status == 200) {
      ordersReady.value = response.data
    }
  } catch (error) {
    //toast.error('Não existem orders prontas de momento')
  }
}

socket.on('readyOrderPublic', (orderReady) => {
  ordersPreparing.value.forEach((order) => {
    if (orderReady.id == order.id) {
      order.status = 'R'
      ordersReady.value.push(order)
      ordersPreparing.value = ordersPreparing.value.filter((elem) => elem.id != order.id)
    }
  })
})

socket.on('deliverOrderPublic', (orderDelive) => {
  ordersReady.value.forEach((order) => {
    if (orderDelive.id == order.id) {
      ordersReady.value = ordersReady.value.filter((elem) => elem.id != order.id)
    }
  })
})

onMounted(() => {
  fetchOrders()
})

</script>

<template>

  <div class="modal-body row">
    <div class="col-md-6">
      <h2>Pending:</h2>
      <div v-for="order in ordersPreparing" :key="order.id">
        <h3>{{ order.ticket_number }}</h3>
      </div>
    </div>
    <div class="col-md-6">
      <h2>Ready:</h2>
      <div v-for="order in ordersReady" :key="order.id">
        <h3 class="text-success">{{ order.ticket_number }}</h3>
      </div>
    </div>
  </div>
</template>
