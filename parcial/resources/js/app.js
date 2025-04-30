import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { createApp } from 'vue';
import AppComponent from './components/AppComponent.vue'; // O donde hayas guardado el componente

createApp(AppComponent).mount('#app');  // Esto debe montar Vue en el div con id="app"
