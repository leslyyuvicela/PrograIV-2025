<template>
  <v-card class="pa-4" elevation="1" :class="{ 'error-border': hasError }">
    <v-row>
      <v-col cols="12">
        <h3 class="fundraising-label text-left">
          Etapas de la recaudación
        </h3>

        <!-- Lista de etapas -->
        <v-col
          v-for="(stage, index) in store.editedFundraising.stages"
          :key="stage.id"
          cols="12"
          class="mb-3"
        >
          <v-card
            class="pa-3 stage-card"
            elevation="1"
          >
            <div class ="stage-content">
              <div class="stage-mumber">Etapa {{ index + 1 }}:</div>
              <div class="stage-title">{{ stage.title }}</div>
            </div>
            <v-menu>
              <template #activator="{ props }">
                <v-btn icon v-bind="props" class="menu-button">
                  <v-btn icon variant="plain" v-bind="props">
                    <Icon icon="mdi:dots-vertical" width="20" height="20" color="text" />
                </v-btn>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="editStage(stage.id)">
                  <v-list-item-title class="menu-item">Editar</v-list-item-title>
                </v-list-item>
                <v-list-item @click="removeStage(stage.id)">
                  <v-list-item-title class="menu-item">Eliminar</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-card>
        </v-col>

        <!-- Botón añadir -->
        <v-col cols="12" class="text-center">
          <v-btn
            class="action-button"
            variant="flat"
            @click="createStage"
          >
            Añadir etapa
          </v-btn>
        </v-col>

        <!-- Mensaje de error -->
        <v-col cols="12" v-if="hasError">
          <p class="text-error text-caption mt-3 text-center">
            Este campo es obligatorio. Debes añadir al menos una etapa.
          </p>
        </v-col>
      </v-col>
    </v-row>
  </v-card>
  <v-dialog v-model="showCancelDialog" max-width="400">
    <v-card class="dialog pa-6">
      <div class="dialog-message">
        ¿Estás seguro de que deseas cancelar la edición de las etapas?
      </div>
      <v-row class="mt-6" justify="space-between">
        <v-btn class="confirm-dialog-button" @click="deleteStage">
          Confirmar
        </v-btn>
        <v-btn class="cancel-dialog-button" @click="showCancelDialog = false">
          Cancelar
        </v-btn>
      </v-row>
    </v-card>
  </v-dialog>
    
</template>

<script setup lang="ts">
import { ref, computed,defineEmits, defineProps } from 'vue';
import {useFundraisingStore} from '@/stores/fundraisingStore';
import {Icon} from '@iconify/vue'

const props = defineProps({
  hasError: {
    type: Boolean,
    default: false
  }
});

const showCancelDialog = ref(false);
const selectedStageId = ref<"string | null">(null);

const store = useFundraisingStore();
const emit = defineEmits(['createStage', 'editStage', 'removeStage']);



const createStage = () => {
  emit('createStage');
};
const editStage = (id: string) => {
  store.startEditingStage(id);
  emit('editStage');
};
const removeStage = (id: string) => {
  selectedStageId.value = id;
  showCancelDialog.value = true;
};

const deleteStage = () => {
  store.deleteStage(selectedStageId.value);
  showCancelDialog.value = false;
  selectedStageId.value = null;
  emit('removeStage')
};

</script>

<style scoped lang="scss">
.text-subtitle-2 {
  color: #676269;
}
.error-border {
  border: 2px solid #ff5252;
  border-radius: 10px;
}
.fundraising-label {
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  color: rgb(var(--v-theme-text));
  font-weight: bold;
}
.action-button {
  background-color: rgb(var(--v-theme-primary));
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  border-radius: 8px;
  color: white;
  padding: 0.5rem 1.5rem;
}
.stage-card{
  background-color: #73D5F5;
  color: #575558;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  border-radius: 8px;
  padding: 16px;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  
  &:hover {
    transition: box-shadow 0.2s ease-in-out;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease-in-out;
    background-color: #5cc3e0;
    transition: transform 0.2s ease-in-out;
    transform: scale(1.01);
  }
}
.stage-number {
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  color: #676269;
  font-weight: bold;
}
.stage-title{
  font-family:  'Montserrat', sans-serif;
  font-size: 16px;
  color: #676269;

}
.stage-content {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  gap: 16px;

}

.menu-item {
  cursor: pointer;
  color: #676269;
  font-family: 'Poppins', sans-serif;
}
.menu-button {
  background: none;
  box-shadow: none;
  padding: 0;
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
  
</style>