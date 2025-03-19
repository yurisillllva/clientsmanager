<template>
  <div v-if="show" class="modal">
    <div class="modal-content">
      <form @submit.prevent="submit">
        <input v-model="form.name" required>
        <input v-model="form.email" type="email" required>
        <input v-model="form.phone" required>
        <input v-model="form.city" required>
        <input v-model="form.state" required>
        <input v-model="form.photo" required>
        <button type="submit">{{ isEditing ? 'Atualizar' : 'Criar' }}</button>
        <button @click="close">Cancelar</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: ['show', 'client'],
  setup() {
    return { v$: useVuelidate() }
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        age: null
      },
      isEditing: false
    }
  },
  validations() {
    return {
      form: {
        name: { required },
        email: { required, email },
        phone: { required },
        age: { numeric }
      }
    }
  },
  watch: {
    client: {
      immediate: true,
      handler(val) {
        if (val) {
          this.form = { ...val };
          this.isEditing = true;
        }
      }
    }
  },
  methods: {
    async submit() {
      const isValid = await this.v$.$validate()
      if (!isValid) return
      
      this.$emit('submit', this.form)
    },
    
    close() {
      this.$emit('close');
    }
  }
}
</script>