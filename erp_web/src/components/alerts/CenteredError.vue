<template>
  <Transition>
    <div v-if="isVisible" class="error-alert flex align-middle space-x-2">
      <span>{{ errorMessage }}</span>
      <button @click.prevent="closeError" class="material-symbols-outlined close-btn" role="button">
        close
      </button>
    </div>
  </Transition>
</template>

<script setup lang="ts">
const props = defineProps(['errorMessage', 'isVisible', 'closeError']);
</script>

<style scoped>
.error-alert {
  @apply rounded-md fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-64 bg-red-500 text-white p-4
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