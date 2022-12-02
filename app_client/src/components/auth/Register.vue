<script setup>
import { ref, inject,onMounted } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/user.js";
const router = useRouter();
const toast = inject("toast");
const axios = inject("axios");
const serverBaseUrl = inject("serverBaseUrl");

const credentials = ref({
  name: "",
  email: "",
  nif: "",
  phone: "",
  password: "",
  paymentType: "",
  default_payment_reference: ""
});


const userStore = useUserStore();

//const emit = defineEmits(["register"]);
/*
const register = async () => {
  if (await userStore.register(credentials.value)) {
    toast.success(
      "User " + userStore.user.name + " has registered sucessfuly."
    );
    emit("register");
    router.push({ name: "Login" });
  } else {
    credentials.value.password = "";
    toast.error("User credentials are invalid!");
  }*/

async function register(credentials) {
  try {
    const response = await axios.post("register", {
      name: credentials.name,
      email: credentials.email,
      nif: credentials.nif,
      phone: credentials.phone,
      password: credentials.password,
      default_payment_type: credentials.paymentType.toString(),
      default_payment_reference: credentials.default_payment_reference,
    });
    console.log(response.data);
    console.log(credentials.paymentType);
    toast.success("User " + credentials.name + " has registered sucessfuly.");
    router.push({ name: "Login" });
  } catch (error) {
    console.log(error);
    credentials.password = "";
    toast.error("Costumer credentials are invalid!");
  }
}
onMounted(() => {
  if (userStore.user) {
    router.push({ name: "home" });
  }
});
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="register">
    <h3 class="mt-5 mb-3">Register</h3>
    <hr />
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputName" class="form-label">Name</label>
        <input
          type="text"
          class="form-control"
          id="inputUsername"
          required
          v-model="credentials.name"
        />
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputEmail" class="form-label">Email</label>
        <input
          type="text"
          class="form-control"
          id="inputEmail"
          required
          v-model="credentials.email"
        />
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputNif" class="form-label">Nif</label>
        <input
          type="text"
          class="form-control"
          id="inputNif"
          required
          v-model="credentials.nif"
        />
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPhone" class="form-label">Phone</label>
        <input
          type="text"
          class="form-control"
          id="inputPhone"
          required
          v-model="credentials.phone"
        />
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPhone" class="form-label">Default Payment Type</label>
        <select
          name="paymentType"
          id="type"
          required
          v-model="credentials.paymentType"
        >
          <option value="visa">Visa</option>
          <option value="paypal">Paypal</option>
          <option value="mbway">MBWay</option>
        </select>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputReference" class="form-label"
          >Default Payment Reference</label
        >
        <input
          type="text"
          class="form-control"
          id="inputReference"
          required
          v-model="credentials.default_payment_reference"
        />
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="inputPassword"
          required
          v-model="credentials.password"
        />
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button v-if="credentials.paymentType"
        type="button"
        class="btn btn-primary px-5"
        @click="register(credentials)"
      >
        Register
      </button>
      <button v-else
      type="button"
      class="btn btn-primary px-5"
      @click="register(credentials)"
      disabled
    >
      Register
    </button>
    </div>
  </form>
</template>
