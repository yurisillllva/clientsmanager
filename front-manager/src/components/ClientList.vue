<template>
  <div>
    <input v-model="search" @input="fetchClients" placeholder="Buscar...">
    <table>
      <tr v-for="client in clients" :key="client.id">
        <td>{{ client.name }}</td>
        <td>{{ client.email }}</td>
        <td>{{ client.phone }}</td>
        <td>
          <button @click="editClient(client)">✏️</button>
          <button @click="deleteClient(client)">🗑️</button>
          <button v-if="client.phone" @click="makeCall(client)">📞</button>
        </td>
      </tr>
    </table>
    <button @click="currentPage--" :disabled="currentPage === 1">Anterior</button>
    <span>Página {{ currentPage }}</span>
    <button @click="currentPage++">Próxima</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      clients: [],
      currentPage: 1,
      search: ''
    }
  },
  methods: {
    async fetchClients() {
      const { data } = await axios.get('clients', {
        params: { page: this.currentPage, search: this.search }
      });
      this.clients = data.data;
    },
    makeCall(client) {
      axios.post(`clients/${client.id}/call`);
    }
  },
  watch: {
    currentPage() {
      this.fetchClients();
    }
  },
  mounted() {
    this.fetchClients();
  }
}
</script>