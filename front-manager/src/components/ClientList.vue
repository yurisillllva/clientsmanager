<template>
  <div class="container" style="max-width: 800px; margin: 0 auto">
    <div class="card">
      <br />
      <div class="d-flex align-items-center gap-2 flex-nowrap">
        <div class="input-group flex-grow-1 mx-3" style="max-width: 300px">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input
            v-model="search"
            type="search"
            class="form-control"
            placeholder="Buscar contato"
          />
        </div>

        <div class="d-flex gap-2 ms-auto">
          <button @click="showCreateModal" class="btn adicionar">
            <i class="bi bi-plus-lg me-2"></i>Adicionar Contato
          </button>
          <button @click="goToCharts" class="btn btn-outline">
            <i class="bi bi-bar-chart"></i>
          </button>
        </div>
      </div>
      <br />

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
              <tr
                v-for="client in filteredClients"
                :key="client.id"
                @click="viewClient(client)"
              >
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar me-3">
                      <img
                        v-if="client.photo"
                        :src="client.photo"
                        alt="Foto do cliente"
                        class="avatar-img"
                      />
                      <span v-else>{{ getInitials(client.name) }}</span>
                    </div>
                    <span>{{ client.name }}</span>
                  </div>
                </td>
                <td>{{ client.email || "--" }}</td>
                <td>{{ client.phone || "--" }}</td>
                <td class="text-end">
                  <div class="btn-group">
                    <button
                      @click.stop="editClient(client)"
                      class="btn btn-sm btn-outline"
                    >
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button
                      v-if="client.phone"
                      @click.stop="openModal(client)"
                      class="btn btn-sm btn-outline"
                    >
                      <i class="bi bi-telephone"></i>
                    </button>
                    <button
                      @click.stop="deleteClient(client)"
                      class="btn btn-sm btn-outline"
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

      <div class="d-flex justify-content-between align-items-center mt-4">
        <button
          @click="goToPreviousPage"
          class="btn btn-outline-secondary"
          :disabled="!pagination.prev"
        >
          Anterior
        </button>
        <span>
          Página {{ pagination.currentPage }} de {{ pagination.lastPage }}
        </span>
        <button
          @click="goToNextPage"
          class="btn btn-outline-secondary"
          :disabled="!pagination.next"
        >
          Próximo
        </button>
      </div>

      <ClientDetailsModal
        :show="showDetailsModal"
        :client="selectedClient"
        @close="closeDetailsModal"
        @delete="handleDeleteFromDetails"
        @editdetails="handleEditFromDetails"
      />
      <ClientModal
        :show="showModal"
        :selectedClient="selectedClient"
        :isEditing="isEditing"
        @submit="handleSubmit"
        @close="closeModal"
        @delete="deleteClient"
        @edit="editClient"
        @editdetails="handleEditFromDetails"
      />
      <DeleteConfirmationModal
        :show="showDeleteModal"
        @confirm="confirmDelete"
        @cancel="closeDeleteModal"
      />
    </div>

    <div v-if="modalVisivel" class="modal">
      <div class="modal-conteudo">
        <span class="fechar" @click="fecharModal">&times;</span>
        <div class="botoes-container">
          <button id="callButton" @click.stop="makeCall">📞 Ligar</button>
          <button id="hangupButton" v-if="this.activeCall" @click.stop="hangup">
            ❌ Desligar
          </button>
          <!-- Exibe o status da ligação -->
          <p id="status">Status: {{ callStatus }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import ClientModal from "./ClientModal.vue";
import ClientDetailsModal from "./ClientDetailsModal.vue";
import DeleteConfirmationModal from "./DeleteConfirmation.vue";
import { Device } from "@twilio/voice-sdk";

export default {
  data() {
    return {
      evice: null,
      modalVisivel: false,
      activeCall: null,
      activeCallClient: null,
      numberToCall: "",
      callStatus: "Desconectado",
      isCalling: false,
      error: null,
      showChartsModal: false,
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
      pagination: {
        currentPage: 1,
        lastPage: 1,
        prev: null,
        next: null,
      },
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
        const matchesType =
          !this.filters.type || clientType === this.filters.type;
        const matchesSearch = Object.values(safeClient).some((value) =>
          String(value).toLowerCase().includes(this.search.toLowerCase())
        );
        return matchesType && matchesSearch;
      });
    },
  },
  methods: {
    abrirModal() {
      this.modalVisivel = true;
    },
    fecharModal() {
      this.modalVisivel = false;
    },
    goToCharts() {
      this.$router.push({ path: "/charts" });
    },
    getInitials(name) {
      if (!name) return "";
      const names = name.split(" ");
      if (names.length >= 2) {
        return `${names[0][0]}${names[1][0]}`.toUpperCase();
      } else if (names.length === 1) {
        return `${names[0][0]}${names[0][1] || ""}`.toUpperCase();
      }
      return "";
    },
    handleEditFromDetails(client) {
      this.selectedClient = client;
      this.isEditing = true;
      this.showModal = true;
      this.showDetailsModal = false;
    },
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
      this.showDetailsModal = true;
    },
    editClient(client) {
      this.selectedClient = client;
      this.isEditing = true;
      this.showModal = true;
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
    openModal(client) {
      this.modalVisivel = true;
      this.activeCallClient = client;
    },
    async makeCall() {
      const client = this.activeCallClient;

      if (this.device) {
        this.device.destroy();
      }

      try {
        const token = await axios.post(`/clients/${client.id}/call`);

        this.device = new Device(token.data.token, {
          codecPreferences: ["opus", "pcmu"],
          enableRingingState: true,
          debug: true,
        });

        this.setupDeviceEvents();

        this.isCalling = true;
        this.callStatus = "Iniciando chamada...";

        this.activeCall = await this.device.connect({
          params: {
            To: token.data.to,
          },
        });

        this.setupCallEvents();
      } catch (error) {
        console.error("Erro ao iniciar chamada:", error);
      }
    },

    hangup() {
      try {
        if (
          this.activeCall &&
          typeof this.activeCall.disconnect === "function"
        ) {
          this.activeCall.disconnect();
          this.activeCall = null; // Limpa a referência
          this.callStatus = "Chamada encerrada";
        } else {
          console.error("Nenhuma chamada ativa para desligar.");
        }
      } catch (error) {
        this.callStatus = "Chamada encerrada";
      }
    },

    setupCallEvents() {
      this.activeCall.on("accept", () => {
        this.callStatus = "Chamada conectada";
        this.isCalling = false;
      });

      this.activeCall.on("disconnect", () => {
        this.activeCall = null;
        this.isCalling = false;
        this.callStatus = "Chamada encerrada";
      });

      this.activeCall.on("error", (error) => {
        this.error = `Erro na chamada: ${error.message}`;
        this.isCalling = false;
      });
    },

    setupDeviceEvents() {
      this.device.on("ready", () => {
        this.callStatus = "Pronto para chamar";
      });

      this.device.on("error", (error) => {
        this.error = `Erro no dispositivo: ${error.message}`;
        this.callStatus = "Erro";
      });

      this.device.on("incoming", (connection) => {
        connection.accept();
        this.activeCall = connection;
        this.callStatus = "Chamada recebida";
      });

      this.device.on("disconnect", () => {
        this.activeCall = null;
        this.isCalling = false;
        this.callStatus = "Chamada encerrada";
      });
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
          await axios.put(`clients/${this.selectedClient.id}`, payload, {
            headers,
          });
        } else {
          await axios.post("clients", payload, { headers });
        }
        await this.fetchClients();
        this.closeModal();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          alert("Erro de validação: Verifique os campos e tente novamente.");
        } else {
          alert(
            "Erro ao salvar: " +
              (error.response?.data?.message || "Erro desconhecido.")
          );
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
        this.pagination = {
          currentPage: data.meta.current_page,
          lastPage: data.meta.last_page,
          prev: data.links.prev,
          next: data.links.next,
        };
      } catch (error) {
        if (error.response && error.response.status === 401) {
          alert(
            "Sua sessão expirou. Você será redirecionado para a página de login."
          );
          window.location.href = "/login";
        } else {
          console.error("Erro ao buscar clientes:", error);
          this.clients = [];
        }
      }
    },
    goToPreviousPage() {
      if (this.pagination.prev) {
        this.currentPage--;
        this.fetchClients();
      }
    },
    goToNextPage() {
      if (this.pagination.next) {
        this.currentPage++;
        this.fetchClients();
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
.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #e1e1e1;
  color: rgb(88, 60, 155);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  font-weight: bold;
}
.avatar-img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}
.adicionar {
  background-color: rgb(88, 60, 155);
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
}
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
.table tbody tr .btn-group {
  display: none;
}
.table tbody tr:hover .btn-group {
  display: flex;
}

.modal {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-conteudo {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 300px;
  border-radius: 10px;
  position: relative;
}

.fechar {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.fechar:hover,
.fechar:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>