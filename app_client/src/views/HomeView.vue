<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useUserStore } from "../stores/user.js"
import axios from 'axios'

const axiosInjected = inject('axios')

const ordersPreparing = ref([])
const ordersReady = ref([])
const toast = inject('toast')
const socket = inject('socket')

const fetchOrders = async () => {
    let response = await axiosInjected.get('orders/P')
    ordersPreparing.value = response.data
     response = await axiosInjected.get('orders/R')
     ordersReady.value = response.data

     console.log(ordersPreparing.value)
      console.log(ordersReady.value)
}

socket.on('readyOrderPublic', (orderReady) => {
    console.log("Recebi do socket")
    //foreach order
    ordersPreparing.value.forEach((order) => {
      if(orderReady.id == order.id){
        order.status = 'R'
        ordersReady.value.push(order)
        ordersPreparing.value = ordersPreparing.value.filter((elem) => elem.id != order.id)
      }	
    })
})

socket.on('deliverOrderPublic', (orderDelive) => {
    console.log("Recebi do socket")
    //foreach order
    ordersReady.value.forEach((order) => {
      if(orderDelive.id == order.id){
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
    <h3>{{order.id}}</h3>
  </div>
  </div>
  <div class="col-md-6">
  <h2>Ready:</h2>
  <div v-for="order in ordersReady" :key="order.id">
    <h3  class="text-success" >{{order.id}}</h3>
  </div>
  </div>
</div>
</template>
