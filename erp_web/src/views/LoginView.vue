<template>
  <div class="flex justify-center items-center min-h-[100vh] md:shadow-inner">
    <Transition>
      <LoginError :errorMessage="errorMessage" :isVisible="isVisible" :closeAlert="closeAlerts" />
    </Transition>
    <Transition>
      <LoginSuccess :message="successMessage" :isVisible="successIsVisible" />
    </Transition>
    <div class="flex flex-col gap-8 items-center rounded-lg px-6 py-8 bg-tertiary">
      <h1 class="text-3xl font-bold tracking-tighter text-secondary">MyERP Admin</h1>
      <form class="inline-block" id="login" @submit.prevent="loginService(user)">
        <fieldset class="space-y-4">
          <legend class="text-lg text-center text-secondary mb-6 font-bold">Login</legend>
          <p class="space-x-2 flex justify-between items-center">
            <label for="email" class="font-bold text-secondary">E-mail</label>
            <input required class="custom-input" type="email" name="email" id="email" v-model="user.email" />
          </p>
          <p class="space-x-2 flex justify-between items-center">
            <label for="password" class="font-bold text-secondary">Password</label>
            <input required class="custom-input" type="password" name="password" id="password"
              v-model="user.password" />
          </p>
        </fieldset>
        <button class="login-btn mt-12" type="submit" form="login" :disabled="isLoading">
          <span v-if="!isLoading">Login</span>
          <svg v-if="isLoading" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="-2 -2 28 28">
            <path fill="currentColor" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"
              opacity=".25" />
            <path fill="currentColor"
              d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z">
              <animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite" type="rotate"
                values="0 12 12;360 12 12" />
            </path>
          </svg>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import LoginError from "../components/alerts/LoginError.vue";
import LoginSuccess from "../components/alerts/LoginSuccess.vue";
import type { AxiosInstance } from "axios";
import type { LoginForm } from "@/types/auth";
import { inject, reactive, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import router from "@/router";

const user: LoginForm = reactive({
  email: 'admin@mail.com',
  password: 'password',
});

const isLoading = ref<boolean>(false);

const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const auth = useAuthStore();
const axios: AxiosInstance = inject<any>('axios');

const loginService = (user: LoginForm) => {
  isLoading.value = true;

  axios.post('/auth/login', user)
    .then((res) => {
      auth.setToken(res.data.access_token); // Insere os dados na store de autenticação.
      auth.setUser(res.data.user);
      showSuccess('Successfuly logged in!'); // Mostrar aviso de login bem sucedido
      setTimeout(() => {
        router.push('/');
      }, 1000);
    })
    .catch((e) => {
      if (e.response) {
        errorMessage.value = e.response?.data?.message ?? 'Authentication error.';
      } else if (e.request) {
        errorMessage.value = 'No server response.';
      } else {
        errorMessage.value = 'Unexpected error. Try again later.';
      }

      showAlert();
      isLoading.value = false;
    });
}

const isVisible = ref<boolean>(false);
const successIsVisible = ref<boolean>(false);

const closeAlerts = (): void => {
  isVisible.value = false;
}

const showSuccess = (msg: string): void => {
  successMessage.value = msg;
  successIsVisible.value = true;
  setTimeout(() => {
    successIsVisible.value = false;
  }, 1000);
}

const showAlert = (): void => {
  isVisible.value = true;
  setTimeout(() => {
    isVisible.value = false;
  }, 1000);
}
</script>

<style scoped>
.login-btn {
  @apply bg-green-600 rounded-full py-1 px-3 w-full mx-auto font-bold text-white text-lg transition ease-out delay-[20ms] flex justify-center
}

.login-btn:hover {
  box-shadow: 0 0 8px 1px rgba(0, 0, 0, 0.2);
}

.login-btn:disabled {
  @apply shadow-none
}

.custom-input {
  @apply px-3 py-1 min-w-[12rem] border rounded-md text-gray-700 leading-tight focus:outline-none focus:border-blue-500
}

.v-enter-active {
  transition: opacity 100ms ease-in;
}

.v-leave-active {
  transition: opacity 2s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

.loading:after {
  content: '';
  display: inline-block;
  width: 1em;
  height: 1em;
  border: 2px solid currentColor;
  border-radius: 50%;
  border-color: transparent transparent currentColor transparent;
  animation: spin 0.5s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>../types/types