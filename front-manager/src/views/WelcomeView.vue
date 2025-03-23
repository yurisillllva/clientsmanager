<template>
  <div class="vh-100 d-flex flex-column align-items-center justify-content-center bg-light">
    <h1 class="mb-4 text-dark display-4">Login</h1>
    <button 
      @click="redirectToLogin"
      class="btn buttonroute btn-lg px-5"
    >
      Fazer login com a Huggy
    </button>
  </div>
</template>

<script>
export default {
  methods: {
    redirectToLogin() {
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped>
.buttonroute {
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