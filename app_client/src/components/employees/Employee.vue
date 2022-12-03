<script setup>
import { ref, watch, inject } from 'vue'  
import { useRouter } from "vue-router";

const axios = inject('axios');
const toast = inject("toast");
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
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>