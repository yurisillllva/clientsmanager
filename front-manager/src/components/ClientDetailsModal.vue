<template>
  <div v-if="show" class="modal" style="display: block">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex align-items-center w-100">
            <div class="avatar me-3">
              {{ clientInitials }}
            </div>

            <div class="me-auto">
              <h4>{{ client.name }}</h4>
            </div>

            <div class="d-flex align-items-center">
              <button
                @click="handleDelete"
                class="btn btn-sm btn-outline me-2"
              >
                <i class="bi bi-trash"></i>
              </button>
              <button
                @click="editClientDetails"
                class="btn btn-sm btn-outline me-2"
              >
                <i class="bi bi-pencil"></i>
              </button>
              <button type="button" class="btn-close" @click="close"></button>
            </div>
          </div>
        </div>

        <div class="modal-body">
          <p>
            Email <strong>{{ client.email || "--" }}</strong>
          </p>
          <p>
            Telefone <strong>{{ client.phone || "--" }}</strong>
          </p>
          <p>
            Endereço <strong>{{ client.address || "--" }}</strong>
          </p>
          <p>
            Bairro <strong>{{ client.neighborhood || "--" }}</strong>
          </p>
          <p>
            Cidade <strong> {{ client.city || "--" }}</strong>
          </p>
          <p>
            Estado <strong>{{ client.state || "--" }}</strong>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="close">
            Fechar
          </button>
        </div>
      </div>
    </div>
    <div class="modal-backdrop" @click="close"></div>
  </div>
</template>

<script>
export default {
  props: ["show", "client"],
  computed: {
    clientInitials() {
      if (!this.client?.name) return "";
      const names = this.client.name.split(" ");
      if (names.length >= 2) {
        return `${names[0][0]}${names[1][0]}`.toUpperCase();
      } else if (names.length === 1) {
        return `${names[0][0]}${names[0][1] || ""}`.toUpperCase();
      }
      return "";
    },
  },
  methods: {
    close() {
      this.$emit("close");
    },
    handleDelete() {
      this.$emit("delete", this.client); // Emite um evento para o componente pai
    },
    editClientDetails() {
      this.$emit("editdetails", this.client); // Emite um evento de edição para o componente pai
    },
  },
};
</script>

<style scoped>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-dialog {
  z-index: 1051;
  pointer-events: auto;
}
.modal-content {
  position: relative;
  z-index: 1052;
}
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1049;
  background-color: rgba(0, 0, 0, 0.5);
  pointer-events: auto;
}
.avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background-color: #007bff;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: bold;
}
</style>