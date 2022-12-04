import { ref, computed, inject, createCommentVNode } from "vue";
import { defineStore } from "pinia";
import { useUserStore } from "@/stores/user.js";

export const useProductsStore = defineStore("products", () => {
  const axios = inject("axios");
  const userStore = useUserStore();

  const products = ref([]);
  const price = ref(0);
  const paymentType = ref("");
  const paymentReference = ref("");

  const totalProducts = computed(() => {
    return products.value.length;
  });

  const showProducts = computed(() => {
    return products.value;
  });

  function insertProduct(newProduct) {
    products.value.push(newProduct);
  }

  function deleteProduct(oldProduct) {
    const index = products.value.findIndex((prd) => prd.id == oldProduct.id);
    if (index >= 0) {
      products.value.splice(index, 1);
    }
  }

  function clearProducts() {
    products.value = [];
  }

  function getPriceAllProducts() {
    let price = 0;
    products.value.forEach((prd) => {
      price += parseFloat(prd.price); //* prd.quantity
    });
    return price.toFixed(2);
  }

  async function getCustomerPayment() {
    const response = await axios.get("customerpayment/" + userStore.userId);
    paymentType.value = response.data;
  }

  async function getCustomerReference() {
    const response = await axios.get("customerreference/" + userStore.userId);

    paymentReference.value = response.data;
  }

  async function postOrderItems(orderItem) {
    const response = await axios.post("orderitems", orderItem);
    console.log(response.data);

    if (response.status == 200) {
      console.log("deu fixe 2x");
      
    }
  }

  getCustomerPayment();
  getCustomerReference();
  async function postProducts() {
    price.value = getPriceAllProducts();

    const response = await axios.post("orders", {
      ticket_number: 99, //TODO
      status: "P",
      customer_id: userStore.userId ? userStore.userId : null,
      total_price: price.value,
      total_paid: price.value, //TODO
      total_paid_with_points: "0.00", //TODO
      points_gained: 0, //TODO
      points_used_to_pay: 0, //TODO
      payment_type: paymentType.value,
      payment_reference: paymentReference.value,
    });

    if (response.status == 200) {
      console.log("deu fixe");

      let order_id = response.data.id;
      var order_local_number = 1; //TODO

      for (let i = 0; i < products.value.length; i++) {
        var product = products.value[i];

        const orderItem = {
          order_id: order_id,
          order_local_number: order_local_number, //TODO
          product_id: product.id,
          status: "W",
          price: product.price,
          preparation_by: 7, //TODO
          notes: "asd", //TODO
        }
        order_local_number = 2; //TODO
        await postOrderItems(orderItem);
      }
    }
    clearProducts();
  }

  return {
    products,
    totalProducts,
    showProducts,
    insertProduct,
    deleteProduct,
    clearProducts,
    postProducts,
  };
});
