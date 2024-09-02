import {
  createRouter,
  createWebHistory,
} from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import HomeView from '../views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import UsersView from "@/views/UsersView.vue";
import RolesView from "@/views/RolesView.vue";
import Dashboards from '@/views/Dashboards.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: {
        auth: true,
      },      
    },
    {
      path: '/dashboards',
      name: 'dashboards',
      component: Dashboards,
      meta: {
        auth: true,
      },      
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {
        clearUser: true,
      },
    },
    {
      path: '/users',
      name: 'users',
      component: UsersView,
      meta: {
        auth: true,
      },
    },
    {
      path: '/roles',
      name: 'roles',
      component: RolesView,
      meta: {
        auth: true,
      },
    },
  ],
});

router.beforeEach(async (to, from, next)  => {
  const auth = useAuthStore();

  if (to.meta?.clearUser) auth.clearUserData();

  if (to.meta?.auth) {    
    if (!localStorage.getItem('access_token')) { // if no token, redirect to login.
      next('/login');
      return;
    }

    const isTokenValid = await auth.verifyToken();

    isTokenValid ? next() : next('/login');
  } else {
    next();
  }  
});

export default router;
