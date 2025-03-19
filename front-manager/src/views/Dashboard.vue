<template>
  <div class="dashboard">
    <header>
      <h1>Meus Clientes</h1>
      <nav>
        <button @click="showCharts">📊 Gráficos</button>
        <button @click="logout">🚪 Sair</button>
      </nav>
    </header>
    
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