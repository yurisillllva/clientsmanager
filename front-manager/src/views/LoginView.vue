<template>
  <div class="vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card p-4 shadow" style="width: 400px;">
      <h2 class="card-title text-center mb-4">Login</h2>
      <form @submit.prevent="login">
        <div class="mb-3">
          <label class="form-label">E-mail</label>
          <input
            v-model="form.email"
            type="email"
            class="form-control"
            required
          >
        </div>
        <div class="mb-4">
          <label class="form-label">Senha</label>
          <input
            v-model="form.password"
            type="password"
            class="form-control"
            required
          >
        </div>
        <button type="submit" class="btn buttonlogin w-100">Entrar</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      form: { email: '', password: '' }
    }
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/login', this.form);
        localStorage.setItem('token', response.data.access_token);
        this.$router.push('/dashboard');
      } catch (error) {
        alert('Erro: ' + error.response?.data?.message || 'Falha no login');
      }
    }
  }
}
</script>
<style scoped>
.buttonlogin {
   background-color: rgb(88, 60, 155); 
  color: white; 
  border: none; 
  padding: 10px 20px; 
  font-size: 16px; 
  font-weight: bold; 
  border-radius: 6px;
  cursor: pointer; 
}
</style>