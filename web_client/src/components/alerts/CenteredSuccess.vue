<template>
  <Transition>
    <div v-if="isVisible" class="success-alert flex align-middle space-x-2">
      <span>{{ successMessage }}</span>
      <button @click.prevent="closeSuccess" class="material-symbols-outlined close-btn" role="button">
        close
      </button>
    </div>
  </Transition>
</template>

<script setup lang="ts">
const props = defineProps(['successMessage', 'isVisible', 'closeSuccess']);
</script>

<style scoped>
.success-alert {
  @apply rounded-md fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-64 bg-green-600 text-white p-4
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.3s ease-in;
}

.v-enter-from,
.v-leave-to {
  opacity: 0; 
}
</style>