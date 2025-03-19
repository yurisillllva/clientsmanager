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

    <div class="row mb-3 g-2">
      <div class="col-md-4">
        <!-- Barra de Busca -->
        <div class="input-group mb-4">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input
            v-model="search"
            type="search"
            class="form-control bi bi-search"
            placeholder="Buscar contato..."
          />
        </div>
      </div>
    </div>

    <!-- Tabela -->
    <div class="card shadow">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th>Nome <i class="bi bi-arrow-down"></i></th>
              <th>Email</th>
              <th>Telefone</th>
              <th class="text-end">Ações</th>
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
            <tr v-for="client in filteredClients" :key="client.id">
              <td>{{ client.name }}</td>
              <td>{{ client.email || "--" }}</td>
              <td>{{ client.phone || "--" }}</td>
              <td class="text-end">
                <div class="btn-group">
                  <button
                    @click="editClient(client)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button
                    v-if="client.phone"
                    @click="makeCall(client)"
                    class="btn btn-sm btn-outline-secondary"
                  >
                    <i class="bi bi-telephone"></i>
                  </button>
                  <button
                    @click="deleteClient(client)"
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
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      showModal: false,
      showDeleteModal: false,
      selectedClient: null,
      clientToDelete: null,
      clients: [],
      currentPage: 1,
      search: "",
      filters: {
        // Adicione esta propriedade
        type: "",
      },
    };
  },
  computed: {
    filteredClients() {
      return this.clients.filter((client) => {
        // Verificação segura de todas as propriedades
        const safeClient = client || {};
        const clientType = safeClient.type || "";
        const matchesType =
          !this.filters.type || clientType === this.filters.type;

        const matchesSearch = Object.values(safeClient).some((value) =>
          String(value).toLowerCase().includes(this.search.toLowerCase())
        );

        return matchesType && matchesSearch;
      });
    },
    isEditing() {
      // Adicione se necessário
      return !!this.selectedClient;
    },
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
          await axios.post("clients", formData);
        }
        this.fetchClients();
        this.closeModal();
      } catch (error) {
        alert("Erro ao salvar!");
      }
    },

    async confirmDelete() {
      await axios.delete(`clients/${this.clientToDelete.id}`);
      this.fetchClients();
      this.closeDeleteModal();
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
          phone: client.phone || "--",
          // Daqui a pouco coloco as outras info
        }));

        this.totalPages = data.last_page;
      } catch (error) {
        console.error("Erro ao buscar clientes:", error);
        this.clients = []; // Garantir array vazio em caso de erro
      }
    },
  },

  async makeCall(client) {
    try {
      await axios.post(`clients/${client.id}/call`);
      alert("Chamada iniciada!");
    } catch (error) {
      alert("Erro ao iniciar chamada");
    }
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
.card {
  border-radius: 8px;
  overflow: hidden;
}

.table {
  margin-bottom: 0;
}

th {
  font-weight: 500;
  padding: 12px 16px !important;
  background-color: #f8f9fa !important;
}

td {
  padding: 12px 16px !important;
  vertical-align: middle;
}

.btn-group .btn {
  margin-left: 8px;
  border-radius: 4px !important;
}

.bi-arrow-down {
  font-size: 0.8em;
  opacity: 0.6;
}
</style>