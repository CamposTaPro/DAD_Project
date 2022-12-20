<script setup>
import { inject } from "vue"
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useUserStore } from "../../stores/user.js"

const serverBaseUrl = inject("serverBaseUrl")
const userStore = useUserStore()
const axios = inject("axios")
const socket = inject('socket')
const toast = inject('toast')

const props = defineProps({
  users: {},
  showId: {
    type: Boolean,
    default: true,
  },
  showEmail: {
    type: Boolean,
    default: true,
  },
  showAdmin: {
    type: Boolean,
    default: true,
  },
  showPhoto: {
    type: Boolean,
    default: true,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(["edit"])

const photoFullUrl = (user) => {
  return user.photo_url
    ? serverBaseUrl + "/storage/fotos/" + user.photo_url
    : avatarNoneUrl
}

const editClick = (user) => {
  emit("edit", user)
}

const canViewUserDetail  = (user) => {
  if (!userStore.user) {
    return false
  }
  if(user.type=='EM' && userStore.user.id!=user.id){
    return false
  }
  return userStore.user.type == 'EM' || userStore.user.id == user.id
}

const canViewUserBlockedandDelete  = (user) => {
  if (!userStore.user) {
    return false
  }
 
  return userStore.user.type == 'EM' && (user.type!='EM');
}

const editBlocked = async (user) => {
  const response = await axios.patch(`users/${user.id}/editblocked`)
  if (response.status == 200){
      toast.success('O utilizador '+user.name+' foi bloqueado/desbloqueado!');
      user.blocked = !user.blocked
      socket.emit('blockOrUnblockUser', user)
  }
}
const deleteUser = async (user) => {

const response = await axios.delete(`users/${user.id}`)
if (response.status == 200){

    toast.success('O utilizador '+user.name+' foi apagado!');
    socket.emit('deleteUser', user)
}

const teste = props.users.indexOf(user)
if (teste > -1) {
  props.users.splice(teste, 1)
}
toast.success('O utilizador '+user.name+' foi apagado!');

}
</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId" class="align-middle">#</th>
        <th v-if="showPhoto" class="align-middle">Photo</th>
        <th class="align-middle">Name</th>
        <th v-if="showEmail" class="align-middle">Email</th>
        <th v-if="showAdmin" class="align-middle">Admin?</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td v-if="showId" class="align-middle">{{ user.id }}</td>
        <td v-if="showPhoto" class="align-middle">
          <img :src="photoFullUrl(user)" class="rounded-circle img_photo" />
        </td>
        <td class="align-middle">{{ user.name }}</td>
        <td v-if="showEmail" class="align-middle">{{ user.email }}</td>
        <td v-if="showAdmin" class="align-middle">{{ user.type == "EM" ? "Sim" : "" }}</td>
        <td class="text-end align-middle" v-if="showEditButton">
          <div class="d-flex justify-content-end" v-if="canViewUserDetail(user)">
            <button class="btn btn-xs btn-light" @click="editClick(user)" v-if="showEditButton">
              <i class="bi bi-xs bi-pencil"></i>
            </button>
          </div>
        </td>
        <td class="text-end align-middle">
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-light" @click="editBlocked(user)" v-if="canViewUserBlockedandDelete(user)" >
              <i v-if="user.blocked == true" class="bi bi-xs bi-lock"></i>
              <i v-else class="bi bi-xs bi-unlock"></i>
            </button>
          </div>
        </td>
        <td class="text-end align-middle" >
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-light" @click="deleteUser(user)" v-if="canViewUserBlockedandDelete(user)">
              <i class="bi bi-xs bi-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>
