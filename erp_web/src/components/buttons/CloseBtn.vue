<template>
  <button class="close-btn" role="button">
    <svg class="fill-secondary hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
  </button>
</template>



<script setup lang="ts">
</script>

<style scoped>
.close-btn {
  @apply rounded-full transition-all ease-out flex items-center justify-center
}

.close-btn:hover {
  @apply bg-red-300 shadow-md
}
</style>