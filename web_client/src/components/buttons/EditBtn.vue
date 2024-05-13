<template>
  <button class="material-symbols-outlined edit-btn" role="button">
    edit
  </button>
</template>

<script setup lang="ts">
</script>

<style scoped>
.edit-btn {
  @apply p-2 transition-all ease-out
}

.edit-btn:hover {
  @apply bg-slate-200 text-black rounded-full shadow-md
}
</style>