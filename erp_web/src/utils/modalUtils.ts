import type { Ref } from 'vue';

export const openModal = (input: Ref): void => {
  input.value = true;
};

export const closeModal = (input: Ref): void => {
  input.value = false;
};
