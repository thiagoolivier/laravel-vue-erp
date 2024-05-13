<template>
  <Transition name="modal">
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-container" @click.stop>
        <div class="flex justify-between items-center">
          <span class="font-normal text-lg">
            <slot name="form-title"></slot>
          </span>        
          <CloseBtn v-if="!hideCloseBtn" @click="closeModal" title="Close modal."/>
        </div>
        <slot></slot>      
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import CloseBtn from "./buttons/CloseBtn.vue";

const props = defineProps({
  showModal: Boolean,
  hideCloseBtn: Boolean,
});

const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-container {
  @apply bg-primary text-secondary max-md:min-w-[90vw] md:min-w-[400px] p-4 rounded shadow-md
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 200ms ease-out;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>