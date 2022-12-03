import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "../stores/user.js"

import HomeView from '../views/HomeView.vue'

import Dashboard from "../components/Dashboard.vue"
import Login from "../components/auth/Login.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
import Tasks from "../components/tasks/Tasks.vue"
import Projects from "../components/projects/Projects.vue"
import Users from "../components/users/Users.vue"
import User from "../components/users/User.vue"
import ProjectTasks from "../components/projects/ProjectTasks.vue"
import Task from "../components/tasks/Task.vue"
import Project from "../components/projects/Project.vue"
import RouteRedirector from "../components/global/RouteRedirector.vue"
import Register from "../components/auth/Register.vue"
import Products from "../components/products/Products.vue"
import Product from "../components/products/Product.vue"
import ProductTable from '../components/products/ProductTable.vue'
import ProductEdit from '../components/products/ProductEdit.vue'
import Carrinho from '../components/Carrinho.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/redirect/:redirectTo',
      name: 'Redirect',
      component: RouteRedirector,
      props: route => ({ redirectTo: route.params.redirectTo})   
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path:'/register',
      name: 'Register',
      component:Register
    },
    {
      path: '/password',
      name: 'ChangePassword',
      component: ChangePassword
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/tasks',
      name: 'Tasks',
      component: Tasks,
    },
    {
      path: '/tasks/current',
      name: 'CurrentTasks',
      component: Tasks,
      props: { onlyCurrentTasks: true, tasksTitle: 'Current Tasks' }
    },
    {
      path: '/projects',
      name: 'Projects',
      component: Projects,
    },
    {
      path: '/projects/new',
      name: 'NewProject',
      component: Project,
      props: { id: -1 }
    },
    {
      path: '/projects/:id',
      name: 'Project',
      component: Project,
      props: route => ({ id: parseInt(route.params.id) })     
    },
    {
      path: '/users',
      name: 'Users',
      component: Users,
    },
    {
      path: '/users/:id',
      name: 'User',
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: route => ({ id: parseInt(route.params.id) })
    }, 
    {
      path: '/projects/:id/tasks',
      name: 'ProjectTasks',
      component: ProjectTasks,
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/projects/:id/tasks/new',
      name: 'NewTaskOfProject',
      component: Task,
      props: route => ({ id:-1, fixedProject:  parseInt(route.params.id) })
    },
    {
      path: '/tasks/new',
      name: 'NewTask',
      component: Task,
      props: { id: -1 }
    },
    {
      path: '/tasks/:id',
      name: 'Task',
      component: Task,
      props: route => ({ id: parseInt(route.params.id) })    
    },
    {
      path: '/reports',
      name: 'Reports',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/products',
      name: 'Products',
      component: Products
    }, 
    {
      path: '/product',
      name: 'Product',
      component: Product
    }, 
    {
      path: '/producttable',
      name: 'ProductTable',
      component: ProductTable
    }, 
    {
      path: '/producttable/:id',
      name: 'ProductEdit',
      component: ProductEdit,
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/carrinho',
      name: 'Carrinho',
      component: Carrinho
    }
  ]
})

let handlingFirstRoute = true

router.beforeEach((to, from, next) => {  
  if (handlingFirstRoute) {
    handlingFirstRoute = false
    next({name: 'Redirect', params: {redirectTo: to.fullPath}})
    return
  } else if (to.name == 'Redirect') {
    next()
    return
  }
  const userStore = useUserStore()  
  if ((to.name == 'Login') || (to.name == 'home')) {
    next()
    return
  }
  /*if (!userStore.user) {
    next({ name: 'Login' })
    return
  }*/
  if (to.name == 'Reports') {
    if (userStore.user.type != 'EM') {
      next({ name: 'home' })
      return
    }
  }
  if (to.name == 'User') {
    if ((userStore.user.type == 'EM') || (userStore.user.id == to.params.id)) {
      next()
      return
    }
    next({ name: 'home' })
    return
  }
  next()
})

export default router
