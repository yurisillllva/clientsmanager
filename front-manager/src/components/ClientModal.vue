<template>
  <div v-if="show" class="modal" style="display: block">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ isEditing ? "Editar Contato" : "Adicionar novo contato" }}
          </h5>
          <button type="button" class="btn-close" @click="close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submit">
            <!-- Campos obrigatórios -->
            <div class="mb-3">
              <label class="form-label">Nome completo *</label>
              <input
                ref="firstInput"
                v-model="form.name"
                type="text"
                class="form-control"
                required
              />
            </div>

            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input
                v-model="form.email"
                type="email"
                class="form-control"
                required
              />
            </div>

            <div class="mb-3">
              <label class="form-label">Telefone *</label>
              <input
                v-model="form.phone"
                type="tel"
                class="form-control"
                required
              />
            </div>

            <!-- Novos campos do layout -->
            <div class="mb-3">
              <label class="form-label">Endereço</label>
              <input v-model="form.address" type="text" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Bairro</label>
              <input
                v-model="form.neighborhood"
                type="text"
                class="form-control"
              />
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Cidade</label>
                <input v-model="form.city" type="text" class="form-control" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Estado</label>
                <input
                  v-model="form.state"
                  type="text"
                  class="form-control"
                  maxlength="2"
                  style="text-transform: uppercase"
                  @input="form.state = $event.target.value.toUpperCase()"
                />
              </div>
            </div>

            <div class="modal-footer mt-4">
              <button type="button" class="btn btn-secondary" @click="close">
                Cancelar
              </button>
              <button
                type="button"
                class="btn btn-primary"
                :disabled="isSaving"
                @click="submit"
              >
                {{ isSaving ? "Salvando..." : "Salvar" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal-backdrop" @click="close"></div>
  </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email, numeric } from "@vuelidate/validators";

export default {
  props: ["show", "client"],
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        phone: "",
        city: "",
        state: "",
        age: null,
        photo: "",
      },
      isSaving: false,
    };
  },
  validations() {
    return {
      form: {
        name: { required },
        email: { required, email },
        phone: { required },
        age: { numeric },
      },
    };
  },
  watch: {
    client: {
      immediate: true,
      handler(val) {
        if (val) {
          this.form = { ...val };
          this.isEditing = true;
        } else {
          this.resetForm();
        }
      },
    },
  },
  mounted() {
    window.addEventListener("keydown", this.handleKeydown);
  },
  beforeUnmount() {
    window.removeEventListener("keydown", this.handleKeydown);
  },
  methods: {
    handleKeydown(event) {
      if (event.key === "Escape") {
        this.close();
      }
    },
    submit() {
      this.$emit("submit", this.form); // Emite os dados do formulário
    },
    close() {
      this.showModal = false;
      this.selectedClient = null;
      this.resetForm(); // Reseta o formulário
    },
    resetForm() {
      this.form = {
        name: "",
        email: "",
        phone: "",
        city: "",
        state: "",
        age: null,
        photo: "",
      };
      this.isEditing = false;
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
</style>