<script setup>
  import { ref, watch, inject } from 'vue'
  import UserDetail from "./UserDetail.vue"
  import { useRouter, onBeforeRouteLeave } from 'vue-router'  
  import Forbidden from '../auth/Forbidden.vue'
  import { useUserStore } from "../../stores/user.js"

  const router = useRouter()  
  const axios = inject('axios')
  const toast = inject('toast')
  const userStore = useUserStore()
  const response = ref('202')

  const props = defineProps({
      id: {
        type: Number,
        default: null
      }
  })

  const newUser = () => {
      return {
        id: null,
        name: '',
        email: '',
        photo_url: null
      }
  }

  let originalValueStr = ''
  const loadUser = (id) => {    
    originalValueStr = ''
      errors.value = null
      if (!id || (id < 0)) {
        user.value = newUser()
        originalValueStr = dataAsString()
      } else {
        axios.get('users/' + id)
          .then((response) => {
            user.value = response.data.data
            originalValueStr = dataAsString()
          })
          .catch((error) => {
            response.value = error.response.status
          })
      }
  }

  const save = () => {
      errors.value = null
      if(user.value.type=='C'){
        axios.patch('users/' + props.id,{
        name: user.value.name,
        email: user.value.email,
        nif: user.value.customer[0].nif,
        phone: user.value.customer[0].phone,
        default_payment_reference: user.value.customer[0].default_payment_reference,
      })
        .then((response) => {
          user.value = response.data.data
          originalValueStr = dataAsString()
          toast.success('User #' + user.value.id + ' was updated successfully.')
          router.back()
        })
        .catch((error) => {
          if (error.response.status == 422) {
              toast.error('User #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('User #' + props.id + ' was not updated due to unknown server error!')
            }
        })
      }else{
      axios.patch('users/' + props.id,{
        name: user.value.name,
        email: user.value.email
      })
        .then((response) => {
          user.value = response.data.data
          originalValueStr = dataAsString()
          toast.success('User #' + user.value.id + ' was updated successfully.')
          router.back()
        })
        .catch((error) => {
          if (error.response.status == 422) {
              toast.error('User #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('User #' + props.id + ' was not updated due to unknown server error!')
            }
        })
      }
  }

  const cancel = () => {
    originalValueStr = dataAsString()
    if(userStore.user.type=='EM'){
    router.push({ name: 'Users' })
  }
  else{
    router.push({ name: 'home' })}

  }

  const dataAsString = () => {
      return JSON.stringify(user.value)
  }

  let nextCallBack = null
  const leaveConfirmed = () => {
      if (nextCallBack) {
        nextCallBack()
      }
  }

  onBeforeRouteLeave((to, from, next) => {
    nextCallBack = null
    let newValueStr = dataAsString()
    if (originalValueStr != newValueStr) {
      nextCallBack = next
      confirmationLeaveDialog.value.show()
    } else {
      next()
    }
  })  

  const user = ref(newUser())
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)

  watch(
    () => props.id,
    (newValue) => {
        loadUser(newValue)
      },
    {immediate: true}  
    )

</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  

  <user-detail v-if="response == 202"
    :user="user"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></user-detail>

  <forbidden v-else>

  </forbidden>
</template>
