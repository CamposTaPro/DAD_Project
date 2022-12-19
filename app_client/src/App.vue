<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router"
import { ref, inject } from "vue"
import { useUserStore } from "./stores/user.js"
import { useProjectsStore } from "./stores/projects.js"
const router = useRouter()
const toast = inject("toast")
const userStore = useUserStore()
const projectsStore = useProjectsStore()
const buttonSidebarExpand = ref(null)
const socket = inject("socket")
const logout = async () => {
  console.log(userStore.user)
    socket.emit('loggedOut', userStore.user)
  if (await userStore.logout()) {
    toast.success("User has logged out of the application.")
    clickMenuOption()
    router.push({ name: 'home' })
  } else {
    toast.error("There was a problem logging out of the application!")
  }
}
const clickMenuOption = () => {
  if (window.getComputedStyle(buttonSidebarExpand.value).display !== "none") {
    buttonSidebarExpand.value.click()
  }
}

socket.on('readyOrder', (order) => {
  toast.success("Order com o id "+order.id+" esta pronta para ser entregue!")
})

</script>
<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <div class="container-fluid">
      <router-link class="navbar-brand col-md-3 col-lg-2 me-0 px-3" :to="{ name: 'home' }" @click="clickMenuOption">
        <img src="@/assets/logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top" />
        Fastuga   
      </router-link>
      <button id="buttonSidebarExpandId" ref="buttonSidebarExpand" class="navbar-toggler" type="button"
        data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item" v-show="!userStore.user">
              <router-link 
                  class="nav-link" 
                  :class="{ active: $route.name === 'Register' }" :to="{ name: 'Register' }"
                  @click="clickMenuOption">
              <i class="bi bi-person-check-fill"></i>
              Register
            </router-link>
          </li>
          <li class="nav-item" v-show="!userStore.user">
            <router-link class="nav-link" :class="{ active: $route.name === 'Login' }" :to="{ name: 'Login' }"
              @click="clickMenuOption">
              <i class="bi bi-box-arrow-in-right"></i>
              Login
            </router-link>
          </li>
          <li class="nav-item dropdown mb-3" > <!--v-show="userStore.user">-->
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <img :src="userStore.userPhotoUrl" class="rounded-circle z-depth-0 avatar-img" alt="avatar image" />
              <span class="avatar-text">{{ userStore.user?.name ?? "Anonymous" }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink" v-show="userStore.user">
              
                <router-link class="dropdown-item"
                  :class="{ active: $route.name == 'User' && $route.params.id == userStore.userId }"
                  :to="{ name: 'User', params: { id: userStore.userId } }" @click="clickMenuOption">
                  <i class="bi bi-person-square"></i>Profile
                </router-link>
              
              
                <hr class="dropdown-divider" />
              
              
                <router-link class="dropdown-item" :class="{ active: $route.name === 'ChangePassword' }"
                  :to="{ name: 'ChangePassword' }" @click="clickMenuOption">
                  <i class="bi bi-key-fill"></i>
                  Change password
                </router-link>
              
              
                <hr class="dropdown-divider" />
              
              
                <a class="dropdown-item" @click.prevent="logout">
                  <i class="bi bi-arrow-right"></i>Logout
                </a>
          
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column w-100 mt-5">
            <li class="nav-item" v-show="userStore.user?.type == 'EM'">
              <router-link class="nav-link" :class="{ active: $route.name === 'ProductTable' }" :to="{ name: 'ProductTable' }"
                @click="clickMenuOption">
                <i class="bi bi-house"></i>
                Product
              </router-link>
            </li>
            <li class="nav-item w-100">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Products' }"
                :to="{ name: 'Products' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-list-stars"></i>
                Products
              </router-link>
            </li>
            <li class="nav-item w-100" v-if="userStore.user?.type == 'C' || userStore.user==null">
              <router-link
                class="nav-link w-100 me-3"
                :class="{ active: $route.name === 'Carrinho' }"
                :to="{ name: 'Carrinho' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-list-check"></i>
                Carrinho
              </router-link>
              <!--<router-link
                class="link-secondary"
                :to="{ name: 'NewTask' }"
                aria-label="Add a new task"
                @click="clickMenuOption"
              >
                <i class="bi bi-xs bi-plus-circle"></i>
              </router-link>-->
            </li>
            <li class="nav-item w-100" v-show="userStore.user?.type == 'EC'||userStore.user?.type == 'EM' ">
              <router-link class="nav-link" :class="{ active: $route.name === 'Kitchen' }" :to="{ name: 'Kitchen' }"
                @click="clickMenuOption">
                <i class="bi bi-people"></i>
                Kitchen
              </router-link>
            </li>
            <li class="nav-item w-100" v-show="userStore.user?.type == 'ED'||userStore.user?.type == 'EM' ">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Caixa' }"
                :to="{ name: 'Caixa' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-files"></i>
                Caixa
              </router-link>
            </li>
            <li class="nav-item w-100" v-show="userStore.user?.type == 'EM'">
              <router-link class="nav-link" :class="{ active: $route.name === 'Users' }" :to="{ name: 'Users' }"
                @click="clickMenuOption">
                <i class="bi bi-people"></i>
                Users
              </router-link>
            </li>
            <li class="nav-item w-100" v-show="userStore.user?.type == 'EM'">
              <router-link class="nav-link" :class="{ active: $route.name === 'Employees' }" :to="{ name: 'Employees' }"
                @click="clickMenuOption">
                <i class="bi bi-people"></i>
                Employees
              </router-link>
            </li>
            <li class="nav-item w-100" v-show="userStore.user?.type == 'C'">
              <router-link class="nav-link" :class="{ active: $route.name === 'Historical' }"
                :to="{ name: 'Historical' }" @click="clickMenuOption">
                <i class="bi bi-bar-chart-line"></i>
                Historical
              </router-link> 
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted"
            v-if="userStore.user">
            <!-- <router-link
              class="link-secondary"
              :to="{ name: 'NewProject' }"
              aria-label="Add a new project"
              @click="clickMenuOption"
            >
              <i class="bi bi-xs bi-plus-circle"></i>
            </router-link> -->
          </h6>
          <ul class="nav flex-column mb-2">
            <!-- <li class="nav-item" v-for="prj in projectsStore.myInprogressProjects" :key="prj.id">
              <router-link
                class="nav-link w-100 me-3"
                :class="{
                  active: $route.name == 'ProjectTasks' && $route.params.id == prj.id,
                }"
                :to="{ name: 'ProjectTasks', params: { id: prj.id } }"
                @click="clickMenuOption"
              >
                <i class="bi bi-file-ruled"></i>
                {{ prj.name }}
              </router-link>
            </li> -->
          </ul>
          
          <div class="d-block d-md-none">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>User</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item" v-show="!userStore.user">
                <a class="nav-link" href="#">
                  <i class="bi bi-person-check-fill"></i>
                  Register
                </a>
              </li>
              <li class="nav-item" v-show="!userStore.user">
                <router-link class="nav-link" :class="{ active: $route.name === 'Login' }" :to="{ name: 'Login' }"
                  @click="clickMenuOption">
                  <i class="bi bi-box-arrow-in-right"></i>
                  Login
                </router-link>
              </li>
              <div v-if="userStore.user" class="d-flex align-items-center">
                <li class="nav-item dropdown " v-show="userStore.user">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img :src="userStore.userPhotoUrl" class="rounded-circle z-depth-1 avatar-img" alt="avatar image" />
                    <span class="avatar-text">User Name</span>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2" >
                  
                      <router-link class="dropdown-item w-100"
                        :class="{ active: $route.name == 'User' && $route.params.id == userStore.userId }"
                        :to="{ name: 'User', params: { id: userStore.userId } }" @click="clickMenuOption">
                        <i class="bi bi-person-square"></i>perfil
                      </router-link>
                     <hr class="dropdown-divider"/>
                    
                      <router-link class="dropdown-item w-100" :class="{ active: $route.name === 'ChangePassword' }"
                        :to="{ name: 'ChangePassword' }" @click="clickMenuOption">
                        <i class="bi bi-key-fill"></i>
                        Change password
                      </router-link>
                                         <hr class="dropdown-divider"/>

                      <a class="dropdown-item" @click.prevent="logout">
                        <i class="bi bi-arrow-right"></i>Logout
                      </a>
                  </ul>
                </li>
              </div>
            </ul>
          </div>
        </div>
      </nav>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>
<style>
@import "./assets/dashboard.css";
.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}
.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}
.dropdown-item {
  font-size: 80%;
}
.btn:focus {
  outline: none;
  box-shadow: none;
}
#sidebarMenu {
  overflow-y: auto;
}

</style>