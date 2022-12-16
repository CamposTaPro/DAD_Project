import { ref, computed, inject, createCommentVNode } from "vue";
import { useRouter, RouterLink, RouterView } from "vue-router";
import { defineStore } from "pinia";
import { useUserStore } from "@/stores/user.js";

export const useProductsStore = defineStore("products", () => {
  const axios = inject("axios");
  const userStore = useUserStore();
  const router = useRouter();

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

  function insertNoteInProduct(product, note, index) {
    if ((note == '') || (product == null)){
      return
    }
    
    const j = products.value.findIndex((prd) => prd.id == product.id);
    if (j >= 0) {
      products.value[index].note = note[index];
      console.log(products.value)
    }
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
      price += parseFloat(prd.price); //TODO * prd.quantity
    });
    return price.toFixed(2);
  }

  //-------------DELETE-------------
  /*async function getCustomerPayment() {
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

  if (userStore.userId == null || userStore.userId == "C") {
    getCustomerPayment();
    getCustomerReference();
  }

  async function postProducts() {
    price.value = getPriceAllProducts();

    const response = await axios.post("orders", {
      ticket_number: 99, //TODO
      status: "P",
      customer_id: userStore.userId ? userStore.userId : null, //TODO
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

      for (let i = 0; i < products.totalProducts; i++) {
        var product = products.value[i];

        const orderItem = {
          order_id: order_id,
          order_local_number: order_local_number, //TODO
          product_id: product.id,
          status: product.type != "hot dish" ? "R" : "W", //VERIFY
          price: product.price,
          preparation_by: null,
          notes: product.note,
        };
        order_local_number = 2; //TODO
        await postOrderItems(orderItem);
      }
    }
    clearProducts();
  }
  //-------------DELETE-------------
  */

  return {
    products,
    totalProducts,
    showProducts,
    insertProduct,
    deleteProduct,
    clearProducts,
    getPriceAllProducts,
    insertNoteInProduct,
  };
});
