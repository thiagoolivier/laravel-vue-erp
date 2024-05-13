<template>
  <button type="button" class="flex items-center justify-center">
    <span class="material-symbols-outlined remove-btn">
      remove
    </span>
  </button>
</template>

<script setup lang="ts"></script>

<style scoped>
  .remove-btn {
    @apply text-secondary hover:text-black rounded-full hover:bg-red-300 shadow-sm transition ease-out delay-[20ms];
  }
</style>