<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-secondary table-component">
      <thead class="text-xs uppercase bg-tertiary text-secondary">
        <slot name="t-head"></slot>
      </thead>
      <tbody>
        <slot name="t-body"></slot>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
</script>