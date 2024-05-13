<template>
  <button class="material-symbols-outlined delete-btn" role="button">
    delete
  </button>
</template>

<script setup lang="ts">
</script>

<style scoped>
.delete-btn {
  @apply p-2 transition-all ease-out
}

.delete-btn:hover {
  @apply bg-red-300 text-black rounded-full shadow-md
}
</style>