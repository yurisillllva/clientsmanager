<template>
  <div class="dashboard">
    <div class="d-flex justify-content-between align-items-center mb-2">
    <header>
      <nav>
        <button @click="logout"><i class="bi bi-box-arrow-left"></i> Sair</button>
      </nav>
    </header>
    </div>
    
    <ClientList />
    <router-view /> <!-- Ver os gŕaficos -->
  </div>
</template>

<script>
import ClientList from '@/components/ClientList.vue'

export default {
  components: { ClientList },
  methods: {
    showCharts() {
      this.$router.push('/dashboard/charts')
    },
    logout() {
      localStorage.removeItem('token')
      this.$router.push('/login')
    }
  }
}
</script>