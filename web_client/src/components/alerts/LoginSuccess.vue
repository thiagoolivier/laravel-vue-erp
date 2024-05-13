<template>
  <div v-if="isVisible" class="success-alert">
    <span class="w-full text-center">{{ message }}</span>
  </div>
</template>

<script setup lang="ts">
const props = defineProps(["message", "isVisible"])
</script>

<style scoped>
.success-alert {
  @apply w-[90vw] md:w-fit rounded-md fixed top-1/3 md:top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-64 bg-green-600 text-white p-4 flex align-middle space-x-2
}
</style>