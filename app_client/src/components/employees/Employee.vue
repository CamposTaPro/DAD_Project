<script setup>
import { ref, watch, inject,onMounted } from 'vue'  
import { useRouter } from "vue-router";
import { useUserStore } from '../../stores/user.js'

const axios = inject('axios');
const toast = inject("toast");
const userStore = useUserStore();
const router = useRouter();
const name = ref('')
const email = ref('')
const password = ref('')
const type= ref('EC')




const createEmployee = async () => {
    
    const employee = {
        name: name.value,
        email: email.value,
        password: password.value,
        type: type.value,
    }


    const response = await axios.post('employee', employee)
    if (response.status == 201) {
        toast.success('Employee #' + employee.name + ' was created successfully.')
        router.push({ name: 'Employees' })
    } else {
        toast.error('Employee was not created due to unknown server error!')
    }

}
onMounted(() => {
    if (userStore.user.type != "EM") {
        router.push({ name: 'home' })
    }
})
</script>


<template>
    <h1>Employee</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="idk">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" v-model="name">
                    </div>
                    <div class="form-group">
                        <label for="description">Email</label>
                        <input type="text" class="form-control" id="email" v-model="email">
                    </div>
                    <div class="form-group">
                        <label for="description">Password</label>
                        <input type="password" class="form-control" id="password" v-model="password">
                    </div>
                    <br>
                    <div>
                        <label>Type</label>
                        <select v-model="type">
                            <option value="EC">Employee Chef</option>
                            <option value="ED">Employee Delivery</option>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" @click="createEmployee">Submit</button>
                    <RouterLink to="/employees" class="btn btn-secondary">Go Back</RouterLink>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>