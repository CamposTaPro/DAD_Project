<script setup>
  import { ref, computed, onMounted, inject, toDisplayString } from 'vue'
  import {useRouter} from 'vue-router'
  import UserTable from "./UserTable.vue"
  import { Bootstrap5Pagination } from 'laravel-vue-pagination';

  const router = useRouter()

  const axios = inject('axios')

  const users = ref({})

  const toast = inject('toast')

  const socket = inject('socket')

  const totalUsers = computed(() => {
    return users.value.length
  })

  socket.on('blockOrUnblockUser', (user) => {

    users.value.data.forEach((u) => {
      if (u.id == user.id) {
        u.blocked = user.blocked
      }
    })
    toast.success('O utilizador '+user.name+' foi bloqueado/desbloqueado!');
})


socket.on('deleteUser', (user) => {
users.value.data.forEach((u, index) => {
  if (u.id == user.id) {
    users.value.data.splice(index, 1)
  }
})

toast.success('O utilizador '+user.name+' foi apagado!');
})

  const loadUsers = (view = 1) => {
    axios.get('users?page='+view)
        .then((response) => {
          users.value = response.data
        })
        .catch((error) => {
          //TODO handle error
        })
    }

  const editUser = (user) => {
    router.push({ name: 'User', params: { id: user.id} })
  }

  onMounted (() => {
    loadUsers()
  })
</script>

<template>
  <h3 class="mt-5 mb-3">Users</h3>
  <hr>
  <user-table
    :users="users.data"
    :showId="false"
    @edit="editUser"
  ></user-table>
  <Bootstrap5Pagination
        :data="users" size="small"
        @pagination-change-page="loadUsers"
    />
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}
</style>

