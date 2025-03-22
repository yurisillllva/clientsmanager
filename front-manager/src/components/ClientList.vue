<template>
  <div class="container" style="max-width: 800px; margin: 0 auto">
    <!-- Cabeçalho e Botões -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Contatos</h2>
      <div>
        <button @click="showCreateModal" class="btn btn-primary">
          <i class="bi bi-plus-lg me-2"></i>Adicionar Contato
        </button>
        <i class="bi bi-bar-chart ms-4"></i>
      </div>
    </div>

    <!-- Tabela de Clientes -->
    <div class="card shadow">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th>Nome <i class="bi bi-arrow-down"></i></th>
              <th>Email</th>
              <th>Telefone</th>
              <th class="text-end"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="clients.length === 0">
              <td colspan="4" class="text-center py-4">
                <div class="text-muted mb-2">Ainda não há contatos</div>
                <button @click="showCreateModal" class="btn btn-link p-0">
                  <i class="bi bi-plus-lg me-1"></i>Adicionar contato
                </button>
              </td>
            </tr>
            <tr v-for="client in filteredClients" :key="client.id" @click="viewClient(client)">
              <td>{{ client.name }}</td>
              <td>{{ client.email || "--" }}</td>
              <td>{{ client.phone || "--" }}</td>
              <td class="text-end">
                <div class="btn-group">
                  <button
                    @click.stop="editClient(client)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button
                    v-if="client.phone"
                    @click.stop="makeCall(client)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    <i class="bi bi-telephone"></i>
                  </button>
                  <button
                    @click.stop="deleteClient(client)"
                    class="btn btn-sm btn-outline-danger"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modais -->
    <ClientDetailsModal
      :show="showDetailsModal"
      :client="selectedClient"
      @close="closeDetailsModal"
      @delete="handleDeleteFromDetails"
    />
    <ClientModal
      :show="showModal"
      :selectedClient="selectedClient"
      :isEditing="isEditing"
      @submit="handleSubmit"
      @close="closeModal"
      @delete="deleteClient"
      @edit="editClient"
    />
    <DeleteConfirmationModal
      :show="showDeleteModal"
      @confirm="confirmDelete"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script>
import axios from "axios";
import ClientModal from "./ClientModal.vue";
import ClientDetailsModal from "./ClientDetailsModal.vue";
import DeleteConfirmationModal from "./DeleteConfirmation.vue";

export default {
  data() {
    return {
      showModal: false,
      showDetailsModal: false,
      showDeleteModal: false,
      selectedClient: null,
      clientToDelete: null,
      clients: [],
      currentPage: 1,
      search: "",
      filters: {
        type: "",
      },
      isEditing: false,
    };
  },
  components: {
    ClientModal,
    ClientDetailsModal,
    DeleteConfirmationModal,
  },
  computed: {
    filteredClients() {
      return this.clients.filter((client) => {
        const safeClient = client || {};
        const clientType = safeClient.type || "";
        const matchesType = !this.filters.type || clientType === this.filters.type;
        const matchesSearch = Object.values(safeClient).some((value) =>
          String(value).toLowerCase().includes(this.search.toLowerCase())
        );
        return matchesType && matchesSearch;
      });
    },
  },
  methods: {
     handleDeleteFromDetails(client) {
      this.clientToDelete = client;
      this.showDeleteModal = true; 
    },
    showCreateModal() {
      this.selectedClient = null;
      this.isEditing = false;
      this.showModal = true;
    },
    viewClient(client) {
      this.selectedClient = client;
      this.showDetailsModal = true; // Abre o modal de detalhes
    },
    editClient(client) {
      this.selectedClient = client;
      this.isEditing = true;
      this.showModal = true; // Abre o modal de edição
    },
    closeModal() {
      this.showModal = false;
      this.selectedClient = null;
    },
    closeDetailsModal() {
      this.showDetailsModal = false;
      this.selectedClient = null;
    },
    deleteClient(client) {
      this.clientToDelete = client;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.clientToDelete = null;
    },
    async makeCall(client) {
      try {
        const response = await axios.post(`/api/clients/${client.id}/call`);
        alert("Chamada iniciada com sucesso! SID: " + response.data.sid);
      } catch (error) {
        console.error("Erro ao iniciar chamada:", error);
        alert("Erro ao iniciar chamada: " + error.response?.data?.error || error.message);
      }
    },
    async handleSubmit(formData) {
      try {
        const headers = {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        };
        const payload = {
          name: formData.name,
          email: formData.email,
          phone: formData.phone,
          address: formData.address || null,
          neighborhood: formData.neighborhood || null,
          city: formData.city || null,
          state: formData.state || null,
          age: formData.age || null,
          photo: formData.photo || null,
        };
        if (this.selectedClient) {
          await axios.put(`clients/${this.selectedClient.id}`, payload, { headers });
        } else {
          await axios.post("clients", payload, { headers });
        }
        await this.fetchClients();
        this.closeModal();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          alert("Erro de validação: Verifique os campos e tente novamente.");
        } else {
          alert("Erro ao salvar: " + (error.response?.data?.message || "Erro desconhecido."));
        }
      }
    },
    async confirmDelete() {
      if (this.clientToDelete) {
        try {
          await axios.delete(`clients/${this.clientToDelete.id}`);
          this.fetchClients();
          this.closeDeleteModal();
          this.closeDetailsModal();
        } catch (error) {
          console.error("Erro ao excluir cliente:", error);
          alert("Erro ao excluir cliente. Tente novamente.");
        }
      }
    },
    async fetchClients() {
      try {
        const { data } = await axios.get("clients", {
          params: {
            page: this.currentPage,
          },
        });
        this.clients = data.data.map((client) => ({
          type: client.type || "--",
          id: client.id,
          name: client.name || "--",
          email: client.email || "--",
          phone: client.phone || "--",
          city: client.city,
          state: client.state,
        }));
        this.totalPages = data.last_page;
      } catch (error) {
        console.error("Erro ao buscar clientes:", error);
        this.clients = [];
      }
    },
  },
  watch: {
    currentPage() {
      this.fetchClients();
    },
  },
  mounted() {
    this.fetchClients();
  },
};
</script>

<style scoped>
.modal {
  z-index: 1055;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-content {
  pointer-events: auto;
}
.modal-backdrop {
  z-index: 1050;
}
.form-select {
  appearance: auto;
}
</style>