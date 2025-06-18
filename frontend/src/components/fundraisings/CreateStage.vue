<style lang="scss" scoped>

.no-border-select .v-field {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  background-color: #73D5F5;
  color: #575558;
  max-width: 250px;    
}

.title{
  font-family: 'Poppins', sans-serif;
  font-size: 25px;
  color: rgb(var(--v-theme-primary));
}

.fundraising-label {
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  color: rgb(var(--v-theme-text));
  font-weight: bold;
}
.fundraising-field {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  color: rgb(var(--v-theme-text));
  max-width: 720px;
  padding: 12px 16px;
}

description-field {
  height: 200px;
}

.v-text-field:hover, .v-textarea:hover{
  scale: 1.05;
  transition: transform 0.2s ease-in-out;
}

.counter-text {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  color: #676269;
}
.text-red {
  color: #f41146 !important;
}

  
  .confirm-btn {
    background-color: #8f00b3;
    color: white;
    font-weight: bold;
    padding: 0.5rem 1.5rem;
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    border-radius: 8px;
  }
  .cancel-btn {
    border: 1px solid rgb(var(--v-theme-secondary));
    color: rgb(var(--v-theme-secondary));
    background-color: white;
    font-weight: bold;
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
    font-size: 20px;
    transition: background-color 0.3s ease;
    &:hover {
      background-color: rgb(var(--v-theme-secondary), 0.1);
      
    }
  }

  .dialog{
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }
  .dialog-title {
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
    font-size: 16px;
    color: #676269;
    text-align: center;
    }

    .dialog-message {
      
      font-family: 'Montserrat', sans-serif;
      font-size: 16px;
      color: #676269;
      text-align: center;
    }

    .cancel-dialog-button {
      background-color: #ffffff;
      color: rgb(var(--v-theme-secondary));
      border: 1px solid #f41146;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
      font-size: 16px;
      padding: 8px 16px;
      transition: background-color 0.3s;

      &:hover {
        background-color: rgb(var(--v-theme-secondary), 0.1)
      }
    }
    .confirm-dialog-button {
      background-color: #f41146;
      color: #ffffff;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
      font-size: 16px;
      padding: 8px 16px;
      transition: background-color 0.3s;

      &:hover {
        background-color: #f2c1f6;
      }
    }
  .maxlength-message {
    color: rgb(var(--v-theme-secondary));
  }
  
</style>

<template>
<v-form v-model="isStageValid" ref="formRef" >
            <div class="mb-4">
              <h3 class="fundraising-label mb-2 text-left">Título</h3>
              <v-text-field
                placeholder="Escribe un título..."
                class="fundraising-field"
                v-model="store.editedStage.title"
                :rules="titleRules"
                maxlength="60"
                variant="outlined"
                hide-details="auto"
                required
                :messages ="store.editedStage.title.length == 60 ? 'Se ha alcanzado el límite de 60 caracteres' : ''"
              >
                <!-- Slot personalizado del contador -->
                <template #counter>
                <span :class="store.editedStage.title.length >= 10 ? 'counter-text' : 'counter-text text-red'">
                    {{ store.editedStage.title.length }} / 60
                </span>
            </template>
            <template v-if="store.editedStage.title.length == 60" #message>
                <span  class="maxlength-message">
                    Se ha alcanzado el límite de 60 caracteres
                </span>
            </template>
        </v-text-field>
    </div>

    <!-- Descripción -->
            <div class="mb-4">
              <h3 class="fundraising-label mb-2 text-left">Descripción</h3>
              <v-textarea
                class="fundraising-field description-field"
                placeholder="Escribe una descripción..."
                v-model="store.editedStage.description"
                :rules="descriptionRules"
                maxlength="500"
                variant="outlined"
                hide-details="auto"
                required
                :messages="store.editedStage.description.length == 500 ? 'Se ha alcanzado el límite de 500 caracteres' : ''"
              >
                  <template #counter>
      <span :class="store.editedStage.description.length >= 100 ? 'counter-text' : 'counter-text text-red'">
        {{ store.editedStage.description.length }} / 500
      </span>
    </template>
    <template v-if="store.editedStage.description.length == 500" #message>
      <span  class="maxlength-message">
        Se ha alcanzado el límite de 500 caracteres
      </span>
    </template>
</v-textarea>
</div>
<!-- Tipo-->
    <div class="mb-4 d-flex align-center">
      <h3 class="fundraising-label">Tipo de donación: </h3>
      <v-select
        class="no-border-select ml-5"
        v-model="store.editedStage.type"
        :items="stageTypes"
        item-title="name"
        item-value="value"
        variant="outlined"
        density="comfortable"
        bg-color="#b3e5fc"
        hide-details="auto"
        required
      ></v-select>
    </div>
    <!-- Fecha Fin -->
    <div class="mb-4">
      <h3 class="fundraising-label mb-2 text-left">Fecha límite</h3>
      <v-text-field
        v-model="store.editedStage.deadline"
        type="date"
        class="fundraising-field"
        variant="outlined"
        :rules="deadlineRules"
        hide-details="auto"
        required
      ></v-text-field>
    </div>

    <!-- Detalles, cambia según el tipo de etapa -->
     <div v-if="store.editedStage.type === 'money'" >
        <!--Cantidad necesitada-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Cantidad necesitada</h3>
          <v-text-field
            v-model="store.editedStage.moneyGoal"
            type="number"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="$00.00"
            :rules="moneyGoalRules"
            prefix="$"
            required
            @blur="formatMoneyGoal"
          ></v-text-field>
        </div>
    </div>

    <div v-else-if="store.editedStage.type === 'goods'">
        <!--Cantidad necesitada (Bienes)-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Cantidad necesitada</h3>
          <v-text-field
            v-model="store.editedStage.goodsGoal"
            type="number"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="0"
            :rules="goodsGoalRules"
            required
          >
        </v-text-field>
        </div>
        <!--Bienes necesitados-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Bienes necesitados</h3>
          <v-text-field
            v-model="store.editedStage.neededGoods"
            type="text"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="Describe los bienes que necesitas"
            :rules="neededGoodsRules"
            :messages="store.editedStage.neededGoods?.length == 200 ? 'Se ha alcanzado el límite de 200 caracteres' : ''"
            required
          >
          <template #counter>
            <span :class="store.editedStage.neededGoods && store.editedStage.neededGoods?.length >= 3 ? 'counter-text' : 'counter-text text-red'">
              {{ store.editedStage.neededGoods?.length }} / 200
            </span>
          </template>
        </v-text-field>
        </div>
        <!--Dirección de entrega-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Dirección de entrega</h3>
          <v-text-field
            v-model="store.editedStage.address"
            type="text"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="Dirección de reunión"
            required
            :rules="addresRules"
            :messages="store.editedStage.address?.length == 200 ? 'Se ha alcanzado el límite de 200 caracteres' : ''"
          >
            <template #counter>
              <span :class="store.editedStage.address && store.editedStage.address?.length >= 50 ? 'counter-text' : 'counter-text text-red'">
                {{ store.editedStage.address?.length }} / 200
              </span>
            </template>
        </v-text-field>
        </div>
    </div>

    <div v-else-if="store.editedStage.type === 'services'">
        <!--Cantidad necesitada (Servicios)-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Personal necesitado:</h3>
          <v-text-field
            v-model="store.editedStage.servicesGoal"
            type="number"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="Cantidad de personal"
            :rules="servicesGoalRules"
            required
          ></v-text-field>
        </div>
        <!--Servicios necesitados-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Servicio necesitado</h3>
          <v-text-field
            v-model="store.editedStage.neededServices"
            type="text"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="Describe el servicio que necesitas"
            :maxlength="200"
            :rules="neededServicesRules"
            :messages="store.editedStage.neededServices?.length == 200 ? 'El servicio necesitado ha alcanzado el límite de 200 caracteres' : ''"
            required
          >
          <template #counter>
            <span :class="store.editedStage.neededServices && store.editedStage.neededServices?.length >= 3 ? 'counter-text' : 'counter-text text-red'">
              {{ store.editedStage.neededServices?.length }} / 200
            </span>
          </template>
          </v-text-field>
        </div>
        <!--Dirección de reunión-->
        <div class="mb-4">
          <h3 class="fundraising-label mb-2 text-left">Dirección</h3>
          <v-text-field
            v-model="store.editedStage.address"
            type="text"
            class="fundraising-field"
            variant="outlined"
            hide-details="auto"
            placeholder="Dirección de reunión"
            required
            :rules="addresRules"
            :messages="store.editedStage.address?.length == 200 ? 'Se ha alcanzado el límite de 200 caracteres' : ''"
          >
          <template #counter>
            <span :class="store.editedStage.address && store.editedStage.address?.length >= 50 ? 'counter-text' : 'counter-text text-red'">
              {{ store.editedStage.address?.length }} / 200
            </span>
          </template>
          </v-text-field>
        </div>
    </div>
    <v-btn
      class="cancel-btn mt-4 "
      @click="showCancelDialog = true"
      >Cancelar
    </v-btn>
    <v-btn
      class="confirm-btn mt-4 ml-8"
      color="primary"
      @click="showSaveDialog = true"
      :disabled="!isStageValid"
      >Guardar Etapa</v-btn>
</v-form>

<v-dialog v-model="showCancelDialog" max-width="400" persistent>
  <v-card class="dialog pa-6">
    <div class="dialog-title">
      ¿Estás seguro de cambiar la creación de la etapa?
    </div>
    <div class="dialog-message">
      Esta acción descartará los cambios ingresados.
    </div>

    <v-row class="mt-6" justify="space-between">
      <v-btn class="confirm-dialog-button" @click="cancelStage">
        Sí, cancelar
      </v-btn>
      <v-btn class="cancel-dialog-button" @click="showCancelDialog = false">
        No, volver
      </v-btn>
    </v-row>
  </v-card>
</v-dialog>

<v-dialog v-model="showSaveDialog" max-width="400" persistent>
  <v-card class="dialog pa-6">
    <div class="dialog-message">
      ¿Estás seguro de que deseas guardar la etapa?
    </div>

    <v-row class="mt-6" justify="space-between">
      <v-btn class="confirm-dialog-button" @click="saveStage">
        Confirmar
      </v-btn>
      <v-btn class="cancel-dialog-button" @click="showSaveDialog = false">
        Cancelar
      </v-btn>
    </v-row>
  </v-card>
</v-dialog>

</template>

<script setup lang="ts">
import { ref, onMounted, defineEmits } from 'vue'
import { useFundraisingStore } from '@/stores/fundraisingStore'
import {BaseStage} from '@/types/fundraisingTypes'
import alertify from 'alertifyjs'

const emit = defineEmits(
  ['close']
)

const store = useFundraisingStore()

const isStageValid = ref(false)
const formRef = ref(HTMLElement)

const showCancelDialog = ref(false)

const showSaveDialog = ref(false)

const previousStage = ref<BaseStage | null>(null) //Guarda la etapa anterior a la que se está editando

const stageTypes = ref([
  { name: 'Monetaria', value: 'money' },
  { name: 'Bienes', value: 'goods' },
  { name: 'Servicios', value: 'services' }
])


const titleRules = [
  (v: string) => !!v || 'El título es obligatorio',
  (v: string) => v.length >= 10 || 'El título debe tener al menos 10 caracteres',
  (v: string) => v.length <= 60 || 'El título no puede exceder los 60 caracteres'
]

const descriptionRules = [
  (v: string) => !!v || 'La descripción es obligatoria',
  (v: string) => v.length >= 100 || 'La descripción debe tener al menos 100 caracteres',
  (v: string) => v.length <= 1000 || 'La descripción no puede exceder los 1000 caracteres'
]
const deadlineRules = [
  (v: string) => !!v || 'La fecha límite es obligatoria',
  (v: string) => new Date(v) > new Date() || 'La fecha límite debe ser posterior a la fecha actual',
  // Validar que la fecha sea posterior a la fecha de la etapa anterior si existe
  (v: string) => !previousStage.value || new Date(v) > new Date(previousStage.value.deadline) || 'La fecha límite de esta etapa debe ser posterior a la de la etapa anterior'
]

const moneyGoalRules = [
  (v: number) => !!v || 'La cantidad es obligatoria',
  //convertir a numero y validar que sea un número positivo
  (v: number) => (Number.isFinite(Number(v)) && Number(v) > 0) || 'La cantidad debe ser un número positivo',
]

const goodsGoalRules = [
  (v: string) => !!v || 'La cantidad de bienes es obligatoria',
  (v: string) => (Number.isInteger(Number(v)) && Number(v) > 0) || 'La cantidad de bienes debe ser un numero entero mayor a cero',
]

const servicesGoalRules = [
  (v: string) => !!v || 'El personal necesitado es obligatorio',
  (v: string) => (Number.isInteger(Number(v)) && Number(v) > 0) || 'El personal necesitado debe ser un numero entero mayor a cero',
]

const neededGoodsRules = [
  (v: string) => !!v || 'La descripción de bienes necesitados es obligatoria',
  (v: string) => v.length >= 3 || 'La descripción de los bienes necesitados debe tener al menos 3 caracteres',
  (v: string) => v.length <= 200 || 'La descripción de los bienes no puede exceder los 200 caracteres'
]

const addresRules = [
  (v: string) => !!v || 'La dirección de entrega es obligatoria',
  (v: string) => v.length >= 50 || 'La dirección debe tener al menos 50 caracteres',
  (v: string) => v.length <= 200 || 'La dirección no puede exceder los 200 caracteres'
]
const neededServicesRules = [
  (v: string) => !!v || 'el servicio necesitado es obligatorio',
  (v: string) => v.length >= 3 || 'El servicio debe tener al menos 3 caracteres',
  (v: string) => v.length <= 200 || 'El servicio no puede exceder los 200 caracteres'
]

function formatMoneyGoal() {
  if (store.editedStage.moneyGoal !== undefined && store.editedStage.moneyGoal !== null && store.editedStage.moneyGoal !== '') {
    store.editedStage.moneyGoal = parseFloat(store.editedStage.moneyGoal).toFixed(2);
  }
}

const saveStage = () => {

  switch (store.editedStage.type) {
    case 'money':
      store.editedStage.goodsGoal = undefined;
      store.editedStage.neededGoods = undefined;
      store.editedStage.servicesGoal = undefined;
      store.editedStage.neededServices = undefined;
      store.editedStage.address = undefined;
      break;
    
    case 'goods':
      store.editedStage.moneyGoal = undefined;
      store.editedStage.neededServices = undefined;
      store.editedStage.servicesGoal = undefined;
      break;
      
    case 'services':
      store.editedStage.moneyGoal = undefined;
      store.editedStage.goodsGoal = undefined;
      store.editedStage.neededGoods = undefined;
      break;
  }

  store.saveEditedStage();
  showSaveDialog.value = false;
  alertify.success('Etapa agregada con éxito');
  emit('close');
}

const cancelStage = () => {
  store.clearEditedStage();
  showCancelDialog.value = false;
  emit('close');
}

onMounted(() => {
  formatMoneyGoal();
  if (!store.isEditingStage){
    //La etapa anterior será la ultima de la lista de etapas de editedFundraising
    previousStage.value = store.editedFundraising.stages[store.editedFundraising.stages.length - 1] || null;
  }
  else {
    //Si se está editando una etapa, se calcula la posición de la etapa que se está y la anterior será la etapa anterior a la que se está editando
    const currentStageIndex = store.editedFundraising.stages.findIndex(stage => stage.id === store.editedStage.id);
    previousStage.value = currentStageIndex > 0 ? store.editedFundraising.stages[currentStageIndex - 1] : null;
  }
}
)
</script>