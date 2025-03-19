<template>
  <form @submit.prevent="login">
    <input v-model="form.email" type="email" required>
    <input v-model="form.password" type="password" required>
    <button>Login</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      form: { email: '', password: '' }
    }
  },
  methods: {
    async login() {
      const { data } = await axios.post('login', this.form);
      localStorage.setItem('token', data.token);
      this.$router.push('/dashboard');
    }
  }
}
</script>