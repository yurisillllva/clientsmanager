import { createApp } from 'vue'
import App from './App.vue'
import axios from 'axios';
import router from './router';
import './assets/main.scss';  // Garanta que este arquivo existe

// Configuração global do Axios
axios.defaults.baseURL = 'http://localhost:8000/api';

const app = createApp(App);

app.config.globalProperties.$axios = axios;
axios.interceptors.request.use(config => {
   const token = localStorage.getItem('token');
   if (token) {
       config.headers.Authorization = `Bearer ${token}`;
   }
   return config;
});

app.use(router)
   .mount('#app');