<script setup>
import { ref, computed, onMounted, inject } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from '../../stores/user.js'
import EmployeeTable from "./EmployeeTable.vue";

const router = useRouter();
const userStore = useUserStore()
const axios = inject("axios");

const users = ref([]);

const loadUsers = () => {
  axios
    .get("users")
    .then((response) => {
      users.value = response.data.data;
      users.value = users.value.filter(function (user) {
        return user.type != "C";
      });
    })
    .catch((error) => {
      console.log(error);
    });
};


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

onMounted(() => {
  if(userStore.user.type!="EM"){
    router.push({ name: 'home' })
  }
  loadUsers();
});
</script>

<template>
  <h3 class="mt-5 mb-3">Employees</h3>
  <router-link to="/employee" class="btn btn-primary">Add Employee</router-link>
  <hr />
  <EmployeeTable
    :users="users"
    :showId="false"
    @edit="editUser"
    @deleteClick="deleteUser"
  ></EmployeeTable>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}
</style>
