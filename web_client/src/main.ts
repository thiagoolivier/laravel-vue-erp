import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import axios from "./plugins/axios";

const applyTheme = (theme: string) => {
  document.documentElement.classList.remove('light', 'dark');
  document.documentElement.classList.add(theme);
};

const theme = localStorage.getItem('theme') || 'light';
applyTheme(theme);

if (!('theme' in localStorage)) {
  localStorage.setItem('theme', 'light');
}

const app = createApp(App);

app.provide("axios", axios);
app.use(router);
app.use(createPinia());
app.mount("#app");
