import { createRouter, createWebHistory } from 'vue-router';
import Welcome from '@/views/WelcomeView.vue';
import Login from '@/views/LoginView.vue';
import Dashboard from '@/views/DashboardView.vue';
import ChartsView from '@/views/ChartsView.vue'; 

const routes = [
  { 
    path: '/',
    component: Welcome,
    meta: { title: 'Bem-vindo' }
  },
  { 
    path: '/login', 
    component: Login,
    meta: { title: 'Login' }
  },
  { 
    path: '/dashboard', 
    component: Dashboard,
    meta: { 
      requiresAuth: true,
      title: 'Dashboard'
    }
  },
  { 
    path: '/charts', 
    component: ChartsView,
    meta: { 
      requiresAuth: true,
      title: 'Gráficos'
    }
  }
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
});


router.beforeEach((to, from, next) => {

  document.title = to.meta.title || 'Huggy Challenge';

  // Verifica o token de autenticação
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('token');
    if (!token) {
      next('/login');
      return;
    }
  }
  
  next();
});

export default router;