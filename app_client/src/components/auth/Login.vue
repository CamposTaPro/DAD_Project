<script setup>
  import { getGlobalThis } from '@vue/shared';
import { ref, inject,onMounted } from 'vue'
  import { useRouter } from 'vue-router'  
  import { useUserStore } from '../../stores/user.js'
  const router = useRouter()  
  const toast = inject('toast')
  
  const credentials = ref({
        username: '',
        password: ''
    })

  const userStore = useUserStore()     

  const emit = defineEmits(['login'])

  const login = async () => {
    if (await userStore.login(credentials.value)) {
      toast.success('User ' + userStore.user.name + ' has entered the application.')
      emit('login')
      router.back()
    } else {
      credentials.value.password = ''
      toast.error('User credentials are invalid!')
    }
  }
onMounted(() => {
    if (userStore.user) {
      router.push({ name: 'home' })
    }
  })
</script>


<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="login"
  >
    <h3 class="mt-5 mb-3">Login</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputUsername"
          class="form-label"
        >Username</label>
        <input
          type="text"
          class="form-control"
          id="inputUsername"
          required
          v-model="credentials.username"
        >
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputPassword"
          class="form-label"
        >Password</label>
        <input
          type="password"
          class="form-control"
          id="inputPassword"
          required
          v-model="credentials.password"
        >
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="login"
      >Login</button>
    </div>
  </form>
</template>

