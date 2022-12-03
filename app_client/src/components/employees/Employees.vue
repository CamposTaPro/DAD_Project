<script setup>
import { ref, computed, onMounted, inject } from "vue";
import { useRouter } from "vue-router";
import EmployeeTable from "./EmployeeTable.vue";

const router = useRouter();

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

onMounted(() => {
  loadUsers();
});
</script>

<template>
  <h3 class="mt-5 mb-3">Employees</h3>
  <hr />
  <EmployeeTable
    :users="users"
    :showId="false"
    @edit="editUser"
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
