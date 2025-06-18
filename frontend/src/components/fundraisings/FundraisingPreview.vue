<style scoped lang="scss">
.campaign-card {
  width: 100%;
  max-width: 320px;
  cursor: pointer;
  transition: box-shadow 0.2s;
}
.campaign-card:hover {
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.title-text {
  font-family: 'Poppins', sans-serif;
  font-size: 18px; /* Título 4 */
  font-weight: 600;
  color: #333333;
  line-height: 1.3;
}

.progress-text {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px; /* Párrafo 2 */
  color: #333333;
  min-width: 36px;
  text-align: right;
}

.single-line {
  white-space: nowrap;         /* No permite saltos de línea */
  overflow: hidden;            /* Oculta el texto que se desborda */
  text-overflow: ellipsis;     /* Muestra "..." al final si se corta */
}

.message-text {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px; /* Párrafo 1 */
  color: #333333;
  margin: 0;
  text-align: left;
  /*Añadir animación hacia arriba al aparecer */
  animation: slideIn 0.5s ease-in-out;
}
@keyframes slideIn {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
  }
</style>

<template>
  <v-card
    class="campaign-card pa-3"
    elevation="2"
    rounded
    @click="showDetails"
  >
    <!-- Imagen -->
    <v-img
      :src="fundraising.imageUrl"
      height="250"
      class="rounded-lg"
      cover
    ></v-img>

    <!-- Título -->
    <div class="text-center mt-3">
      <h4 class="title-text">{{ fundraising.title }}</h4>
    </div>

    <!-- Progreso -->
    <div class="d-flex align-center justify-space-between mt-3">
      <v-progress-linear
        :model-value="fundraising.progress"
        height="8"
        color="#E987FF"
        rounded
        class="flex-grow-1 mr-2"
      ></v-progress-linear>
      <span class="progress-text">{{ fundraising.progress }}%</span>
    </div>

    <!-- Mensaje alternante -->
    <div class="mt-2">
      <p class="message-text" v-if="displayedMessage === 'money'">
        Recaudado: ${{ fundraising.moneyRaised}}
      </p>
      <p class="message-text single-line" v-else-if="displayedMessage === 'goods'">
        Recaudado: {{ fundraising.goodsRaised }} {{ fundraising.goodsType || 'artículos' }}
      </p>
      <p class="message-text" v-else-if="displayedMessage === 'services'">
        Participantes: {{ fundraising.servicesRaised }} 
      </p>
    </div>
  </v-card>
</template>


<script setup lang="ts">
import { ref, computed, onMounted, defineProps } from 'vue';

import { FundraisingPreview } from "@/types/fundraisingTypes";
import { useRouter } from 'vue-router';

// FundraisingPreview data as a prop
const props = defineProps<{
  fundraising: FundraisingPreview;
}>();

const displayedMessage = ref<'money' | 'goods' | 'services'>('services');

function showDetails() {
  const router = useRouter();
  router.push({ name: 'FundraisingDetails', params: { id: props.fundraising.id } });
}

// Cambia displayedMessage entre 'money', 'goods' y 'services', pero si la prop fundraising no tiene alguna de las propiedades moneyRaised, goodsRaised o servicesRaised, se salta esa opción
const changeMessage = () => {
  switch (displayedMessage.value) {
    case 'money':
      displayedMessage.value = 'goods';
      if (!props.fundraising.goodsRaised) {
        changeMessage();
      }
      break;
    case 'goods':
      displayedMessage.value = 'services';
      if (!props.fundraising.servicesRaised) {
        changeMessage();
      }
      break;
    case 'services':
      displayedMessage.value = 'money';
      if (!props.fundraising.moneyRaised) {
        changeMessage();
      }
      break;
  }
};

// Establece un contador para cambiar el mensaje cada 5 segundos
onMounted(() => {
  changeMessage();
  setInterval(changeMessage, 3000);
});


</script>