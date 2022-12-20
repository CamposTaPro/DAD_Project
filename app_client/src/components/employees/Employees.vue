<script setup>
import { ref, computed, onMounted, inject, toDisplayString } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from '../../stores/user.js'
import EmployeeTable from "./EmployeeTable.vue";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

const router = useRouter();
const userStore = useUserStore()
const axios = inject("axios");
const toast = inject("toast");
const users = ref({});

const loadUsers = (view = 1) => {
  axios
  .get("employees?page=" + view)
    .then((response) => {
      users.value = response.data;
      users.value.data = users.value.data.filter(function (user) {
        return user.type != "C";
      });
    })
    .catch((error) => {
      //TODO handle error -- (deve estar bom)
      toast.error(`Error loading users ${error.message}`);
    });
};


  const editUser = (user) => {
    router.push({ name: 'User', params: { id: user.id} })
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
    :users="users.data"
    :showId="false"
    @edit="editUser"
  ></EmployeeTable>
  
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
