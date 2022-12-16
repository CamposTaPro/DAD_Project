<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import UserTable from "./UserTable.vue"
  import { useUserStore } from "../../stores/user.js"

  const userStore = useUserStore()
  
  const router = useRouter()

  const axios = inject('axios')

  const users = ref([])

  const totalUsers = computed(() => {
    return users.value.length
  })

  const loadUsers = () => {
    axios.get('users')
        .then((response) => {
          users.value = response.data.data
        })
        .catch((error) => {
          console.log(error)
        })
    }

  const editUser = (user) => {
    console.log(user)
    router.push({ name: 'User', params: { id: user.id} })
  }
  const deleteUser = async (id) =>{
    let response = await axios.delete(`users/${id}`)
    if(response.status == 200){
      loadUsers()
    }
  }

  onMounted (() => {
    loadUsers()
  })
</script>

<template>
  <h3 class="mt-5 mb-3">Users</h3>
  <h3>Total: {{totalUsers}}</h3>
  <hr>
  <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
    @deleteClick="deleteUser"
  ></user-table>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}
</style>

