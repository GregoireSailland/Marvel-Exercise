import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
{
  path: '/',
  name: 'Home',
  component: Home
},
{
  path: '/about',
  name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/settings',
    name: 'Settings',
    component: () => import(/* webpackChunkName: "about" */ '../views/Settings.vue')
  }
  ]

  const router = createRouter({
    history: createWebHistory(process.env.NODE_ENV === 'production'?process.env.VUE_APP_BASE_URL:process.env.BASE_URL),
    routes
  })

  export default router
