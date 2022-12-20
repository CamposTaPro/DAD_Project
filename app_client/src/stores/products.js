import { ref, computed, inject, createCommentVNode } from "vue";
import { useRouter, RouterLink, RouterView } from "vue-router";
import { defineStore } from "pinia";
import { useUserStore } from "@/stores/user.js";

export const useProductsStore = defineStore("products", () => {
  const axios = inject("axios");
  const userStore = useUserStore();
  const router = useRouter();

  const products = ref([]);
  const ticket = ref(0);
  const price = ref(0);
  const paymentType = ref("");
  const paymentReference = ref("");

  function getTicket() {
    if (ticket.value == 99) {
      ticket.value = 1;
    }
    ticket.value += 1;
    return ticket.value;
  }

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
    if (note == "" || product == null) {
      return;
    }

    const j = products.value.findIndex((prd) => prd.id == product.id);
    if (j >= 0) {
      products.value[index].note = note[index];
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
      price += parseFloat(prd.price); //TODO - verificar se é necessario (* prd.quantity)
    });
    return price.toFixed(2);
  }

  return {
    products,
    totalProducts,
    showProducts,
    insertProduct,
    deleteProduct,
    clearProducts,
    getPriceAllProducts,
    insertNoteInProduct,
    getTicket,
  };
});
