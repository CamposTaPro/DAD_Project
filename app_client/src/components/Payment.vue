<script setup>
import { ref, inject, onMounted, watch } from 'vue'
import { useRouter, RouterLink, RouterView } from "vue-router"
import { useProductsStore } from '@/stores/products.js'
import { useUserStore } from "@/stores/user.js";
import axios from 'axios'

const products = useProductsStore()
const router = useRouter();
const userStore = useUserStore();

const axiosInjected = inject('axios')

const type = ref('');
const reference = ref('');


const fetchUser = async () => {
    try {
        const response = await axiosInjected.get('users/me')
        type.value = response.data.data.customer[0].default_payment_type;
        reference.value = response.data.data.customer[0].default_payment_reference;
    } catch (error) {
        throw error
    }
}

const validateForm = () => {
    if (reference.value == '') {
        //TODO: alert
        alert('Reference is required')
        return;
    }
    if (type.value == '') {
        //TODO: alert
        alert('Type is required')
        return;
    }
    if (type.value == 'VISA' && isNaN(reference.value)) {
        //TODO: alert
        alert("On type 'VISA', Reference must be a number")
        return;
    }
    if (type.value == 'VISA' && reference.value.length != 16) {
        //TODO: alert
        alert("On type 'VISA', Reference have 16 numbers")
        return;
    }
    if (type.value == 'VISA' && reference.value[0] == 0) {
        //TODO: alert
        alert("On type 'VISA', Reference can't start with '0'")
        return;
    }
    if (type.value == 'PAYPAL' && (!reference.value.includes('@') || !reference.value.includes('.'))) {
        //TODO: alert
        alert("On type 'PAYPAL', Reference has to contain the correct format of an email")
        return;
    }
    if (type.value == 'MBWAY' && reference.value[0] == 0) {
        alert("On type 'MBWAY', Reference can't start with '0'")
        return;
    }
    if (type.value == 'MBWAY' && isNaN(reference.value)) {
        //TODO: alert
        alert("On type 'MBWAY', Reference must be a number")
        return;
    }
    if (type.value == 'MBWAY' && reference.value.length != 9) {
        //TODO: alert
        alert("On type 'MBWAY', Reference have 9 numbers")
        return;
    }
}

const postOrderItems = async (orderItem) => {
    const response = await axiosInjected.post("orderitems", orderItem);
    console.log(response.data);

    if (response.status == 200) {
        console.log("deu fixe 2x");
    }
}

const createOrder = async () => {
    var userId
    if (userStore.userId == -1){
        userId = null
    } else {
        userId = userStore.userId
    }

    const response = await axiosInjected.post("orders", {
        ticket_number: 99, //TODO
        status: "P",
        customer_id: userId,
        total_price: products.getPriceAllProducts(),
        total_paid: products.getPriceAllProducts(), //TODO
        total_paid_with_points: "0.00", //TODO
        points_gained: 0, //TODO
        points_used_to_pay: 0, //TODO
        payment_type: type.value,
        payment_reference: reference.value,
    });

    if (response.status == 200) {
        console.log("deu fixe");

        let order_id = response.data.id;

        for (var i = 0; i < products.totalProducts; i++) {
            var product = products.products[i];
            var order_local_number = i+1;
            const orderItem = {
                order_id: order_id,
                order_local_number: order_local_number,
                product_id: product.id,
                status: product.type != "hot dish" ? "R" : "W", //VERIFY
                price: product.price,
                preparation_by: null,
                notes: product.note,
            }
            await postOrderItems(orderItem);
        }
    } 
    products.clearProducts();
    //TODO: alert
    router.push('/')
}

const verifyPayment = async () => {
    var value = products.getPriceAllProducts();

    const response = await axios.post("https://dad-202223-payments-api.vercel.app/api/payments", {
        type: type.value.toLowerCase(),
        reference: reference.value,
        value: Number(value)
    });
    console.log(response.status)
    if (response.status == 201) {
        console.log("yau");
        createOrder();
    }
    if (response.status == 422) {
        //TODO: alert - indicar o motivo do erro
        alert("Payment not valid")
    }
}

onMounted(() => {
    if (products.totalProducts <= 0) {
        router.push('/')
    }
    if (userStore.user) {
        fetchUser();
    }
})

</script>

<template>
    <h1>Payment</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="validateForm">
                    <div class="form-group">
                        <label for="reference">Reference</label>
                        <input type="text" class="form-control" id="reference" v-model="reference">
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" v-model="type">
                            <option value="VISA">VISA</option>
                            <option value="PAYPAL">PAYPAL</option>
                            <option value="MBWAY">MBWAY</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Value: {{ products.getPriceAllProducts() }}</p>
                    </div>
                    <button type="submit" class="btn btn-primary" @click="verifyPayment">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scope>

</style>