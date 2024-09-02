<template>
  <div class="flex flex-col min-h-screen bg-primary">
    <!-- Renderiza o menu de navegação somente se a rota não for a de login ou registro -->
    <header v-if="!isLoginRoute" class="bg-tertiary">
      <nav class="custom-nav">
        <div>
          <button @click.prevent type="button" class="flex items-center justify-center p-2">
            <span class="material-symbols-outlined text-secondary" @click="setTheme()">
              brightness_4
            </span>
          </button>
        </div>

        <div class="flex col-start-2 justify-center space-x-6 text-secondary">
          <RouterLink to="/">
            <span :class="{ 'selected-nav-option': currentRoutePath === '/' }">Home</span>
          </RouterLink>
          <RouterLink to="/dashboards">
            <span :class="{ 'selected-nav-option': currentRoutePath === '/dashboards' }">Dashboards</span>
          </RouterLink>
          <RouterLink to="/users">
            <span :class="{ 'selected-nav-option': currentRoutePath === '/users' }">Users</span>
          </RouterLink>
          <RouterLink to="/roles">
            <span :class="{ 'selected-nav-option': currentRoutePath === '/roles' }">Roles</span>
          </RouterLink>
        </div>

        <div class="flex col-start-3 justify-end items-center space-x-4">
          <button @click="isOptionsVisible = !isOptionsVisible" @focusout="isOptionsVisible = false" type="button"
            class="user-option">
            <span class="material-symbols-outlined text-secondary">
              account_circle
            </span>
            <span class="text-secondary">
              {{ getLocalStorageUser().name }}
            </span>
            <span class="material-symbols-outlined text-secondary" :class="{ 'rotate-180': isOptionsVisible }">
              arrow_drop_down
            </span>
          </button>
          <Transition name="option-card">
            <ul v-show="isOptionsVisible" class="user-floating-card bg-white dark:bg-primary">
              <li>
                <a href="#" @click="confirmLogout" class="w-full block p-1 text-secondary hover:text-primary">Logout</a>
              </li>
            </ul>
          </Transition>
        </div>
      </nav>
    </header>
    <RouterView />

    <!-- logout confirmation modal -->
    <Modal :showModal="isConfirmVisible" @close="isConfirmVisible = false" :hideCloseBtn="true">
      <div class="flex flex-col gap-6">
        <p class="text-lg text-center">You really want to log out?</p>
        <div class="flex justify-center gap-4">
          <button @click="triggerLogout" class="red-btn" type="button">
            <span class="px-2">Yes</span>
          </button>
          <button @click="isConfirmVisible = false" class="green-btn" type="button">
            <span class="px-2">No</span>
          </button>
        </div>
      </div>
    </Modal>

    <!-- alerts -->
    <CenteredError :isVisible="isErrorVisible" :errorMessage="errorMessage" :closeError="closeError" />
    <CenteredSuccess :isVisible="isSuccessVisible" :successMessage="successMessage" :closeSuccess="closeSuccess" />
  </div>
</template>

<script setup lang="ts">
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router';
import { ref } from "vue";
import { getLocalStorageUser } from "./utils/userUtils";
import { closeError, closeSuccess, showSuccess, showError } from "@/utils/alertUtils";
import Modal from "./components/Modal.vue";
import CenteredError from "./components/alerts/CenteredError.vue";
import CenteredSuccess from "./components/alerts/CenteredSuccess.vue";
import axios from "axios";

const router = useRouter();
const currentRoutePath = ref<string>(window.document.location.pathname);
const isLoginRoute = ref<boolean>(window.document.location.pathname === '/login');
const isDarkMode = ref<boolean>(false);

const isOptionsVisible = ref<boolean>(false);
const isConfirmVisible = ref<boolean>(false);

const isErrorVisible = ref<boolean>(false);
const isSuccessVisible = ref<boolean>(false);

const successMessage = ref<string>('');
const errorMessage = ref<string>('');

const isLoading = ref<boolean>(false);

const setTheme = () => {
  isDarkMode.value = !isDarkMode.value;

  if (isDarkMode.value) {
    localStorage.setItem('theme', 'dark');
    document.documentElement.classList.add('dark');
    document.documentElement.classList.remove('light');
  } else {
    localStorage.setItem('theme', 'light');
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
  }
}

router.beforeResolve((to, from, next) => {
  to.path === '/login' ? isLoginRoute.value = true : isLoginRoute.value = false;
  next();
});

router.afterEach((to) => {
  currentRoutePath.value = to.path;
});

const confirmLogout = () => {
  isConfirmVisible.value = true;
}

const logout = () => {
  router.push('/login');
  isConfirmVisible.value = false;
}

const triggerLogout = () => {
  isLoading.value = true;

  axios.get('/auth/logout')
    .then((res) => {
      showSuccess('Successfuly logged out!', successMessage, isSuccessVisible);
      isConfirmVisible.value = false;
      setTimeout(() => {
        router.push('/login');
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

      showError(errorMessage.value, errorMessage, isErrorVisible);
      isLoading.value = false;
    });
}
</script>

<style scoped>
.custom-nav {
  @apply grid md:grid-cols-3 p-4 shadow-md items-center
}

.custom-nav>a {
  @apply hover:text-secondary
}

.selected-nav-option {
  @apply border-b-2 border-secondary text-secondary
}

.user-option {
  @apply flex items-center space-x-1 border-2 border-secondary rounded-full py-1 px-2 transition ease-out delay-75
}

.user-option:focus {
  @apply shadow-md
}

.user-floating-card {
  @apply absolute top-[3.5rem] right-2.5 border-2 border-secondary rounded-full w-[12rem] shadow-md transition ease-out delay-75 p-1
}

.user-floating-card>li {
  @apply w-full rounded-full text-center hover:bg-gray-200
}

.option-card-enter-active,
.option-card-leave-active {
  transition: opacity 0.2s ease;
}

.option-card-enter-from,
.option-card-leave-to {
  opacity: 0;
}
</style>
