import type { Ref } from 'vue';

export const showError = (
  message: string,
  errorMsgRef: Ref,
  isErrorVisibleRef: Ref
): void => {
  errorMsgRef.value = message;
  isErrorVisibleRef.value = true;
  setTimeout(() => {
    closeError(isErrorVisibleRef);
  }, 2000);
};

export const showSuccess = (
  message: string,
  successMsgRef: Ref,
  isSuccessVisibleRef: Ref
): void => {
  successMsgRef.value = message;
  isSuccessVisibleRef.value = true;
  setTimeout(() => {
    closeSuccess(isSuccessVisibleRef);
  }, 2000);
};

export const closeError = (input: Ref): void => {
  input.value = false;
};

export const closeSuccess = (input: Ref): void => {
  input.value = false;
};
