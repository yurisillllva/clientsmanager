<template>
  <div v-if="show" class="modal" style="display: block">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{
              isEditing
                ? "Editar Contato"
                : selectedClient
                ? selectedClient.name
                : "Adicionar novo contato"
            }}
          </h5>
          <div v-if="!isEditing && selectedClient" class="ms-auto">
            <button
              @click="editClient"
              class="btn btn-sm btn-outline-secondary me-2"
            >
              <i class="bi bi-pencil"></i>
            </button>
            <button @click="deleteClient" class="btn btn-sm btn-outline-danger">
              <i class="bi bi-trash"></i>
            </button>
          </div>
          <button type="button" class="btn-close" @click="close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submit">
            <div class="mb-3">
              <label class="form-label">Nome completo *</label>
              <input
                ref="firstInput"
                v-model="form.name"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': v$.form.name.$error }"
              />
              <div v-if="v$.form.name.$error" class="invalid-feedback">
                Campo obrigatório.
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input
                v-model="form.email"
                type="email"
                class="form-control"
                :class="{ 'is-invalid': v$.form.email.$error }"
              />
              <div v-if="v$.form.email.$error" class="invalid-feedback">
                Campo obrigatório e deve ser um email válido.
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Telefone *</label>
              <input
                v-model="form.phone"
                type="tel"
                class="form-control"
                :class="{ 'is-invalid': v$.form.phone.$error }"
                :disabled="isEditing"
              />
              <div v-if="v$.form.phone.$error" class="invalid-feedback">
                Campo obrigatório.
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Endereço</label>
              <input
                v-model="form.address"
                type="text"
                class="form-control"
                :disabled="isEditing"
              />
            </div>
            <div class="mb-3">
              <label class="form-label">Bairro</label>
              <input
                v-model="form.neighborhood"
                type="text"
                class="form-control"
                :disabled="isEditing"
              />
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Cidade</label>
                <input
                  v-model="form.city"
                  type="text"
                  class="form-control"
                  :disabled="isEditing"
                />
              </div>
              <div class="col-md-6">
                <label class="form-label">Estado</label>
                <input
                  v-model="form.state"
                  type="text"
                  class="form-control"
                  maxlength="2"
                  style="text-transform: uppercase"
                  :disabled="isEditing"
                />
              </div>
            </div>
            <div class="modal-footer mt-4">
              <button type="button" class="btn btn-light" @click="close">
                Cancelar
              </button>
              <button
                type="button"
                class="btn savemodal"
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
import { required, email} from "@vuelidate/validators";

export default {
  props: ["show", "selectedClient", "isEditing"],
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        phone: "",
        address: "",
        neighborhood: "",
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
      },
    };
  },
  watch: {
    selectedClient: {
      immediate: true,
      handler(client) {
        if (client) {
          this.form = { ...client };
        } else {
          this.resetForm();
        }
      },
    },
  },
  methods: {
    editClient() {
      this.$emit("edit");
    },
    deleteClient() {
      this.$emit("delete", this.selectedClient);
    },
    submit() {
      this.v$.$touch(); 
      if (this.v$.$invalid) {
        return; 
      }
      this.$emit("submit", this.form);
      this.resetForm();
  this.close();  
    },
    close() {
      this.resetForm();
      this.$emit("close");
    },
    resetForm() {
      this.form = {
        name: "",
        email: "",
        phone: "",
        address: "",
        neighborhood: "",
        city: "",
        state: "",
        age: null,
        photo: "",
      };
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
is-invalid {
  border-color: #dc3545 !important;
}
.invalid-feedback {
  color: #dc3545;
  font-size: 0.875em;
}
.savemodal {
   background-color: rgb(88, 60, 155); 
  color: white; 
  border: none; 
  padding: 10px 20px; 
  font-size: 16px; 
  font-weight: bold; 
  border-radius: 6px;
  cursor: pointer; 
}
</style>