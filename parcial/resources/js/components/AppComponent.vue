<template>
  <div>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container-fluid">
        <!-- Logo de la ONG -->
        <a class="navbar-brand" href="#">
          <img src="/images/logo.png" alt="Logo ONG" width="150">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav"> <!-- Sin ms-auto aquí para alinearlo a la izquierda -->
            <li class="nav-item" :class="{ active: vista === 'donaciones' }">
              <a class="nav-link" @click="vista = 'donaciones'" href="#">Donaciones</a>
            </li>
            <li class="nav-item" :class="{ active: vista === 'informes' }">
              <a class="nav-link" @click="vista = 'informes'" href="#">Informes</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Componentes -->
    <div v-if="vista === 'donaciones'">
      <DonacionesComponent />
    </div>
    <div v-if="vista === 'informes'">
      <InformesComponent />
    </div>
  </div>
</template>

<script>
import DonacionesComponent from './DonacionesComponent.vue';
import InformesComponent from './InformesComponent.vue';

export default {
  data() {
    return {
      vista: 'donaciones'  // Inicializa con 'donaciones' activa
    };
  },
  components: {
    DonacionesComponent,
    InformesComponent
  }
}
</script>

<style scoped>

</style>
