//Html
<template>
<header>
      <div class="logo">
        <img src="../../public/img/logo_completo.png" alt="Donamiga Logo" />
      </div>
      <nav>
        <a href="#" class="active">Inicio</a>
        <a href="#">Sobre nosotros</a>
        <a href="#">Donaciones</a>
        <a href="/create-campaign">Recaudar</a>
      </nav>
      <div class="auth-buttons">
        <button class="login"
        @click="$router.push({ name: 'login' })"
        >Iniciar Sesión</button>
        <button class="register"
        @click="$router.push({ name: 'signup' })"
        >Regístrate</button>
      </div>
    </header>

    <main>
      <div class="hero">
    <img src="../../public/img/fondo_home.jpeg" alt="Banner" class="hero-image" />
    <div class="hero-text">
      <p>Pequeñas acciones, grandes cambios.</p>
    <strong>En <span class="highlight">DONAMIGA</span> creemos en la fuerza de ayudar con el corazón.</strong>
  </div>
</div>

      <section class="campaigns">
        <h2>Estas personas necesitan de tu ayuda</h2>
        <div class="cards">
          <div class="card">
            <img src="https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FRecaudacion1.png?alt=media&token=bfd66b4c-4c7d-4c0f-91d9-c83f82c20ab5" alt="Niña con doctora" />
            <p>Lili necesita ayuda para sus quimioterapias</p>
            <div class="progress">
              <div class="bar" style="width: 55%"></div>
            </div>
            <div class="progress-text">
              <span>$1,000 recaudados</span><span>55%</span>
            </div>
          </div>
          <div class="card">
            <img src="https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FRecaudacion2.png?alt=media&token=93b43ad8-82f7-42ff-91f9-2ebe87a80571" alt="Daños tormenta" />
            <p>Daños por tormenta Julia en el oriente</p>
            <div class="progress">
              <div class="bar" style="width: 75%"></div>
            </div>
            <div class="progress-text">
              <span>$9,100 recaudados</span><span>75%</span>
            </div>
          </div>
          <div class="card">
            <img src="https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FRecaudacion3.png?alt=media&token=aa903371-cb91-484f-a7b0-f51f3ece8ed1" alt="Casa en construcción" />
            <p>Construcción de casa para afectados por tormenta Julia</p>
            <div class="progress">
              <div class="bar" style="width: 75%"></div>
            </div>
            <div class="progress-text">
              <span>$8,000 recaudados</span><span>75%</span>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer>
      <div class="footer-links">
        <a href="#">Términos y condiciones</a>
        <a href="#">Política de Privacidad</a>
        <a href="#">Sobre nosotros</a>
      </div>
      <p>© 2025 Donamiga. Todos los derechos reservados.</p>
    </footer>
</template>

<script lang="ts" setup>
import { useRouter } from 'vue-router';
import useFundraisingStore from '@/stores/fundraisingStore';
import {ref, onMounted} from 'vue';

const fundraisingStore = useFundraisingStore();
const router = useRouter();

onMounted(() => {
  fundraisingStore.loadRecommendedFundraisings();
}),
</script>

<style scoped>
body {
  font-family: 'Inter', sans-serif;
  margin: 0;
  padding: 0;
  background: #FFFFFF;
  color: #333;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: #FFFFFF;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.logo {
  display: flex;
  align-items: center;
}

.logo img {
  height: 32px;
  margin-right: 8px;
}

nav a {
  margin: 0 1rem;
  text-decoration: none;
  color: #333;
  font-weight: 500;
}

nav a.active {
  color: #8F00B3;
}

.auth-buttons button {
  margin-left: 1rem;
  padding: 0.5rem 1rem;
  border: 2px solid #e6007e;
  background: white;
  color: #e6007e;
  font-weight: 600;
  border-radius: 6px;
  cursor: pointer;
}

.auth-buttons .login {
  background-color: #ffccd9;
}

.auth-buttons .register {
  background-color: white;
}

.hero {
  position: relative;
  width: 100%;
  height: calc(100vh - 80px); 
  overflow: hidden;
}

.hero img {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  border-radius: 0;
}

.hero-text {
  position: absolute;
  top: 10%; 
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  color: #F41146;
  font-size: 1.1rem;
  max-width: 90%;
  line-height: 1.5;
  font-weight: 500;
}

.campaigns {
  padding: 2rem;
  text-align: center;
}

.campaigns h2 {
  margin-bottom: 2rem;
}

.cards {
  display: flex;
  justify-content: space-around;
  gap: 1rem;
  flex-wrap: wrap;
}

.card {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  width: 300px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 0.5rem;
}

.card p {
  font-weight: 500;
  margin: 0.5rem 0;
}

.progress {
  background: #eee;
  height: 12px;
  border-radius: 10px;
  overflow: hidden;
  margin: 0.5rem 0;
}

.progress .bar {
  height: 100%;
  background: #e6007e;
}

.progress-text {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
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
