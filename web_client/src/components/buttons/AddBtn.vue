<template>
  <button type="button" class="flex items-center justify-center">
    <span class="material-symbols-outlined add-btn">
      add
    </span>
  </button>
</template>

<script setup lang="ts"></script>

<style scoped>
.add-btn {
  @apply text-secondary hover:text-black rounded-full hover:bg-blue-300 shadow-sm transition ease-out delay-[20ms];
}
</style>