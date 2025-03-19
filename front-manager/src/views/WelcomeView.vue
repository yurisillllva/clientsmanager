<template>
  <div class="vh-100 d-flex flex-column align-items-center justify-content-center bg-light">
    <h1 class="mb-4 text-dark display-4">Login</h1>
    <button 
      @click="redirectToLogin"
      class="btn btn-primary btn-lg px-5"
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
/* .welcome-page {
  @apply min-h-screen flex flex-col items-center justify-center bg-white;
} */
</style>