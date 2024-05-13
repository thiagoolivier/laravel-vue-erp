<template>
  <div v-if="isVisible" class="error-alert">
    <span class="w-full text-center">{{ errorMessage }}</span>
    <button @click.prevent="closeAlert">
      <svg class="fill-white" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
      </svg>
    </button>
  </div>
</template>

<script setup lang="ts">
const props = defineProps(["errorMessage", "isVisible", "closeAlert"])
</script>

<style scoped>
.error-alert {
  @apply w-[90vw] md:w-fit rounded-md fixed top-1/3 md:top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-64 bg-red-500 text-white p-4 flex align-middle justify-between space-x-2
}
</style>