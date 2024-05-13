import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import axios from "./plugins/axios"

if (('theme' in localStorage)) {
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.classList.remove('dark');
      document.documentElement.classList.add('light');
    } else {
      document.documentElement.classList.remove('light');
      document.documentElement.classList.add('dark');
    }
} else {
    localStorage.setItem('theme', 'light');
    document.documentElement.classList.remove();
    document.documentElement.classList.add('light');
}


const app = createApp(App);

app.provide("axios", axios);
app.use(router);
app.use(createPinia());
app.mount("#app");
