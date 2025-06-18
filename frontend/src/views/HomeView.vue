<style lang="css" scoped>

.hero{
  background-image: url('../../public/img/fondo_home.jpeg');
  background-size: cover;
  background-position: center;
  height: 70vh;
  width: 80vw;
  margin: 0 auto;
  margin-top: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
}

.welcome-card {
  background: rgba(255, 255, 255, 0.7);
  color: red;
  padding: 2rem;
  border-radius: 10px;
  margin: 10% 15%;
  max-width: 400px;
}
.welcome-text {
  text-align: center;
  font-weight: 600;
  font-family: poppins, sans-serif;
}

footer {
  background: #2c003e;
  color: white;
  padding: 2rem;
  text-align: center;
  
}

.footer-links {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-bottom: 1rem;
}

.footer-links a {
  color: white;
  text-decoration: none;
  font-weight: 600;
}



</style>

<template>
  <NavBar/>
  <div class="home">
    <div class="hero">
      <div class="welcome-card">
        <p class="welcome-text">
          Pequeñas acciones, grandes cambios.
          <br /> <br />
          En DONAMIGA creemos en la fuerza de ayudar con el corazón.
        </p>
      </div>
    </div>
    <v-container class="mt-5">
      <h2>Estas personas necesitan de tu ayuda</h2>
      <v-row>
        <v-col cols="12" sm="6" md="4" lg="3" v-for="fundraising in fundraisings" :key="fundraising.id">
          <fundraising-preview :fundraising="fundraising" />
        </v-col>
      </v-row>
    </v-container>
    <footer>
      <div class="footer-links">
        <a href="#">Términos y condiciones</a>
        <a href="#">Política de Privacidad</a>
        <a href="#">Sobre nosotros</a>
      </div>
      <p>© 2025 Donamiga. Todos los derechos reservados.</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { useFundraisingStore } from '@/stores/fundraisingStore';
import FundraisingPreview from '@/components/fundraisings/FundraisingPreview.vue';
import { onMounted } from 'vue';
import NavBar from '@/components/general/NavBar.vue';

const fundraisingStore = useFundraisingStore();
const fundraisings = fundraisingStore.recommendedFundraisings;
</script>
