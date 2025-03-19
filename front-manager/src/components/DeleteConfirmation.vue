<template>
  <div v-if="show" class="modal">
    <div class="modal-content">
      <p>Excluir este cliente?</p>
      <button @click="$emit('confirm')">Excluir</button>
      <button @click="$emit('cancel')">Cancelar</button>
    </div>
  </div>
</template>