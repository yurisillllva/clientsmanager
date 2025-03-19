<template>
  <div>
    <input v-model="search" @input="fetchClients" placeholder="Buscar...">
    <div>
    <button @click="showCreateModal">Novo Cliente</button>
    
    <ClientModal
      :show="showModal"
      :client="selectedClient"
      @submit="handleSubmit"
      @close="closeModal"
    />

    <DeleteConfirmation
      :show="showDeleteModal"
      @confirm="confirmDelete"
      @cancel="closeDeleteModal"
    />
  </div>
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
      showModal: false,
      showDeleteModal: false,
      selectedClient: null,
      clientToDelete: null,
      clients: [],
      currentPage: 1,
      search: ''
    }
  },
  methods: {
    showCreateModal() {
      this.selectedClient = null;
      this.showModal = true;
    },

    editClient(client) {
      this.selectedClient = client;
      this.showModal = true;
    },

    deleteClient(client) {
      this.clientToDelete = client;
      this.showDeleteModal = true;
    },

    async handleSubmit(formData) {
      try {
        if (this.isEditing) {
          await axios.put(`clients/${this.selectedClient.id}`, formData);
        } else {
          await axios.post('clients', formData);
        }
        this.fetchClients();
        this.closeModal();
      } catch (error) {
        alert('Erro ao salvar!');
      }
    },

    async confirmDelete() {
      await axios.delete(`clients/${this.clientToDelete.id}`);
      this.fetchClients();
      this.closeDeleteModal();
    },

    async fetchClients() {
      try {
        const { data } = await axios.get('clients', {
          params: { 
            page: this.currentPage, 
            search: this.search 
          }
        });
        this.clients = data.data;
        this.totalPages = data.last_page;
      } catch (error) {
        console.error('Erro ao buscar clientes:', error);
      }
    },

    async makeCall(client) {
      try {
        await axios.post(`clients/${client.id}/call`);
        alert('Chamada iniciada!');
      } catch (error) {
        alert('Erro ao iniciar chamada');
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
}
</script>