<script setup>
import { ref, watch, inject, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/user.js";

const axios = inject("axios");
const toast = inject("toast");
const userStore = useUserStore();
const router = useRouter();
const name = ref("");
const email = ref("");
const password = ref("");
const type = ref("EC");

const createEmployee = async () => {
  try {
    const employee = {
      name: name.value,
      email: email.value,
      password: password.value,
      type: type.value,
    };

    const response = await axios.post("employee", employee);
    toast.success("Employee #" + employee.name + " was created successfully.");
    router.push({ name: "Employees" });
  } catch (error) {
    if (error.response.data.errors.name) {
      toast.error(error.response.data.errors.name[0]);
    }
    if (error.response.data.errors.email) {
      toast.error(error.response.data.errors.email[0]);
    }
    if (error.response.data.errors.password) {
      toast.error(error.response.data.errors.password[0]);
    }
    if (error.response.data.errors.type) {
      toast.error(error.response.data.errors.type[0]);
    }
  }
};
onMounted(() => {
  if (userStore.user == null || userStore.user.type != "EM") {
    router.push({ name: "home" });
  }
});
</script>

<template>
  <h1>Employee</h1>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <form @submit.prevent="idk">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model="name" />
          </div>
          <div class="form-group">
            <label for="description">Email</label>
            <input type="text" class="form-control" id="email" v-model="email" />
          </div>
          <div class="form-group">
            <label for="description">Password</label>
            <input type="password" class="form-control" id="password" v-model="password" />
          </div>
          <br />
          <div>
            <label>Type</label>
            <select v-model="type">
              <option value="EC">Employee Chef</option>
              <option value="ED">Employee Delivery</option>
            </select>
          </div>
          <br />
          <button type="submit" class="btn btn-primary" @click="createEmployee">
            Submit
          </button>
          <RouterLink to="/employees" class="btn btn-secondary">Go Back</RouterLink>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
