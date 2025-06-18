import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";
import { vuetify } from './plugins/vuetify'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import 'alertifyjs/build/css/alertify.min.css'
import 'alertifyjs/build/css/themes/default.min.css'
import {  setupStoreSync } from './plugins/pinia-sync'
import './styles/vuetify-overrides.css'
import './styles/main.css'

// 👇 Tus stores aquí
import { useAuthStore } from "./stores/authStore";

const app = createApp(App);
app.use(router);
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
app.use(pinia);
app.use(vuetify);

setupStoreSync({
    auth: useAuthStore,
    })

app.mount("#app");
