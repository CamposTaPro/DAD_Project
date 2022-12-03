<script setup>
import { ref, watch, computed, inject, onMounted } from "vue";
import avatarNoneUrl from "@/assets/avatar-none.png";

const serverBaseUrl = inject("serverBaseUrl");
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false,
  },
});

const emit = defineEmits(["save", "cancel"]);

const editingUser = ref(props.user);
const aux= ref(props.user);
watch(
  () => props.user,
  (newUser) => {
    editingUser.value = newUser;
  },
  { immediate: true }
);

const photoFullUrl = computed(() => {
  return editingUser.value.photo_url
    ? serverBaseUrl + "/storage/fotos/" + editingUser.value.photo_url
    : avatarNoneUrl;
});

const save = () => {
  emit("save", editingUser.value);

};

const cancel = () => {
  emit("cancel", editingUser.value);
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">User #{{ editingUser.id }}</h3>
    <hr />
    <div class="d-flex flex-wrap justify-content-between">
      <div class="w-75 pe-4">
        <div class="mb-3">
          <label for="inputName" class="form-label">Name</label>
          <input
            type="text"
            class="form-control"
            id="inputName"
            placeholder="User Name"
            required
            v-model="editingUser.name"
          />
          <field-error-message
            :errors="errors"
            fieldName="name"
          ></field-error-message>
        </div>

        <div class="mb-3 px-1">
          <label for="inputEmail" class="form-label">Email</label>
          <input
            type="email"
            class="form-control"
            id="inputEmail"
            placeholder="Email"
            required
            v-model="editingUser.email"
          />
          <field-error-message
            :errors="errors"
            fieldName="email"
          ></field-error-message>
        </div>
        <div class="mb-3 px-1" v-if="editingUser.type == 'C'">
          <label for="inputEmail" class="form-label">Nif</label>
          <input
            type="payment_type"
            class="form-control"
            id="inputEmail"
            placeholder=""
            required
            v-model="editingUser.customer[0].nif"
          />
          <field-error-message
            :errors="errors"
            fieldName="payment_nif"
          ></field-error-message>
        </div>
        <div class="mb-3 px-1" v-if="editingUser.type == 'C'">
          <label for="inputEmail" class="form-label">Phone</label>
          <input
            type="payment_type"
            class="form-control"
            id="inputEmail"
            placeholder=""
            required
            v-model="editingUser.customer[0].phone"
          />
          <field-error-message
            :errors="errors"
            fieldName="phone"
          ></field-error-message>
        </div>
        <div class="mb-3 px-1" v-if="editingUser.type == 'C'">
          <label for="inputEmail" class="form-label"
            >Default Payment Reference</label
          >
          <input
            type="payment_type"
            class="form-control"
            id="inputEmail"
            placeholder="Email"
            required
            v-model="editingUser.customer[0].default_payment_reference"
          />
          <field-error-message
            :errors="errors"
            fieldName="payment_reference"
          ></field-error-message>
        </div>
      </div>
      <div class="w-25">
        <div class="mb-3">
          <label class="form-label">Photo</label>
          <div class="form-control text-center">
            <img :src="photoFullUrl" class="w-100" />
          </div>
        </div>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">
        Save
      </button>
      <button type="button" class="btn btn-light px-5" @click="cancel">
        Cancel
      </button>
    </div>
  </form>
</template>

<style scoped>
.total_hours {
  width: 26rem;
}
</style>