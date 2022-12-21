import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "../stores/user.js"

import HomeView from '../views/HomeView.vue'

import Login from "../components/auth/Login.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
import Users from "../components/users/Users.vue"
import User from "../components/users/User.vue"
import RouteRedirector from "../components/global/RouteRedirector.vue"
import Register from "../components/auth/Register.vue"
import Products from "../components/products/Products.vue"
import Product from "../components/products/Product.vue"
import ProductTable from '../components/products/ProductTable.vue'
import ProductEdit from '../components/products/ProductEdit.vue'
import Carrinho from '../components/Carrinho.vue'
import Employees from "../components/employees/Employees.vue"
import Employee from "../components/employees/Employee.vue"
import Historical from '../components/customers/Historical.vue'
import Payment from '../components/Payment.vue'
import Caixa from '../components/delivery/Caixa.vue'
import Kitchen from "../components/chef/Kitchen.vue"
import FinishDish from "../components/chef/FinishDish.vue"
import Statistics from "../components/Statistics.vue"

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
    },
    {
      path:'/employees',
      name: 'Employees',
      component: Employees
    },
    {
      path:'/employee',
      name: 'Employee',
      component: Employee

    },
    {
      path: '/kitchen',
      name: 'Kitchen',
      component: Kitchen
    },
    {
      path: '/FinishDish',
      name: 'FinishDish',
      component: FinishDish
    }, 
    {
      path:'/historical',
      name: 'Historical',
      component: Historical
    },
    {
      path: '/caixa',
      name: 'Caixa',
      component: Caixa
    },
    {
      path:'/payment',
      name: 'Payment',
      component: Payment
    },
    {
      path:'/statistics',
      name: 'Statistics',
      component: Statistics
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
