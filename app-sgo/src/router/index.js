import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import NotFound from '../views/NotFound'
import Login from '../views/Login.vue'
import Logout from '../views/Logout.vue'
import Usuarios from '../views/Usuarios.vue'
import About from '../views/About.vue'
import Agregados from '../views/Agregados.vue'
import Prefijos from '../views/Prefijos.vue'


Vue.use(VueRouter)

const routes = [{
    path: '/home',
    name: 'home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import( /* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  linkActiveClass: 'is-active',
  routes: [{
      path: '/home',
      name: 'home',
      component: Home,
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/',
      redirect: '/home'
    },
    {
      path: '/logout',
      name: 'logout',
      component: Logout,
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/404',
      name: '404',
      component: NotFound,
    },
    {
      path: '*',
      redirect: '/404',
    },
    {
      path: '/about',
      name: 'about',
      component: About,
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/Agregados',
      name: 'Agregados',
      component: Agregados,
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/Prefijos',
      name: 'Prefijos',
      component: Prefijos,
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/usuarios',
      name: 'usuarios',
      component: Usuarios,
      meta: {
        requiresAuth: true,
      }
    },
  ]
})

export default router