import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import Toaster from "@meforma/vue-toaster";
import FieldErrorMessage from './components/global/FieldErrorMessage.vue'
import ConfirmationDialog from './components/global/ConfirmationDialog.vue'
import { io } from "socket.io-client"
import App from './App.vue'
import router from './router'
//import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css"
import "bootstrap"



const app = createApp(App)

const apiDomain = import.meta.env.VITE_API_DOMAIN
const wsConnection = import.meta.env.VITE_WS_CONNECTION

const socketIO= new io({
  connection: wsConnection,
})

const serverBaseUrl = `${apiDomain}`
app.provide('axios', axios.create({
    baseURL: serverBaseUrl + '/api',
    headers: {
      'Content-type': 'application/json',
    },
  }))
app.provide('serverBaseUrl', serverBaseUrl)  
app.provide('socket', io("http://localhost:8080"))

app.config.globalProperties.$serverUrl= apiDomain

app.use(Toaster, {
    // Global/Default options
    position: 'top',
    timeout: 3000,
    pauseOnHover: true
})

app.provide('toast', app.config.globalProperties.$toast);

app.use(createPinia())
app.use(router)

app.component('FieldErrorMessage', FieldErrorMessage)
app.component('ConfirmationDialog', ConfirmationDialog)

app.mount('#app')
