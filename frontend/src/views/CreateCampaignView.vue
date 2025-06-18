<template>
  <NavBar></NavBar>
  <v-container class="pa-4" fluid>
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card class="pa-6">
          <!-- Logo y Título -->
          <div class="text-center mb-8">
            <img src="/icons/donamiga_logo.png" alt="Logo" style="max-width: 250px" class="mb-4" />
            <h1 class="title mb-6">{{ view === 'create-fundraising' ? 'Crear recaudación' : store.stageEditingMode ? 'Editar etapa' : 'Crear etapa' }}</h1>
          </div>

          <!-- Formulario -->
          <v-form v-model="isFormValid" @submit.prevent v-if="view === 'create-fundraising'" ref="formRef">
            <v-expansion-panels>
            <v-expansion-panel class="mb-6">
              <v-expansion-panel-title class="text-left">
                <h2 class="fundraising-label">Información de la recaudación</h2>
              </v-expansion-panel-title>
            <v-expansion-panel-text>
            <!-- Título -->
            <div class="mb-4">
              <h3 class="fundraising-label mb-2 text-left">Título</h3>
              <v-text-field
                placeholder="Escribe un título..."
                class="fundraising-field"
                v-model="store.editedFundraising.title"
                :rules="titleRules"
                maxlength="60"
                variant="outlined"
                hide-details="auto"
                required
                :messages ="store.editedFundraising.title.length == 60 ? 'Se ha alcanzado el límite de 60 caracteres' : ''"
              >
                <!-- Slot personalizado del contador -->
    <template #counter>
      <span :class="store.editedFundraising.title.length >= 10 ? 'counter-text' : 'counter-text text-red'">
        {{ store.editedFundraising.title.length }} / 60
      </span>
    </template>
    <template v-if="store.editedFundraising.title.length ==60"  #message>
      <span class="maxlength-message">
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
                v-model="store.editedFundraising.description"
                :rules="descriptionRules"
                maxlength="1000"
                variant="outlined"
                hide-details="auto"
                required
                :messages="store.editedFundraising.description.length == 1000 ? 'Se ha alcanzado el límite de 1000 caracteres' : ''"
              >
                  <template #counter>
      <span :class="store.editedFundraising.description.length >= 100 ? 'counter-text' : 'counter-text text-red'">
        {{ store.editedFundraising.description.length }} / 1000
      </span>
    </template>
        <template v-if="store.editedFundraising.description.length == 1000" #message>
      <span  class="maxlength-message">
        Se ha alcanzado el límite de 1000 caracteres
      </span>
    </template>
            </v-textarea>
            </div>

            <!-- Categoría -->
            <div class="mb-4 d-flex align-center">
              <h3 class="fundraising-label">Categoría: </h3>
              <v-select
                class="no-border-select ml-5"
                v-model="store.editedFundraising.category"
                :items="categories"
                item-title="name"
                item-value="id"
                :rules="[v => !!v || 'Selecciona una categoría']"
                placeholder="Seleccione"
                variant="outlined"
                density="comfortable"
                bg-color="#b3e5fc"
                hide-details="auto"
                required
              ></v-select>
            </div>
            </v-expansion-panel-text>
          </v-expansion-panel>
            
          <v-expansion-panel class="mb-6">
            <v-expansion-panel-title class="text-left">
              <h2 class="fundraising-label">Etapas de la recaudación</h2>
            </v-expansion-panel-title>
            <v-expansion-panel-text>
            <!-- Etapas -->
            <div class="mb-6">
              <CampaignStageList ref="stageList" @createStage="createStage" :hasError="stageListError" @editStage="editStage" />
            </div>
            </v-expansion-panel-text>
            </v-expansion-panel>

            <v-expansion-panel class="mb-6">
              <v-expansion-panel-title class="text-left">
                <h2 class="fundraising-label">Evidencias y verificación</h2>
              </v-expansion-panel-title>
            <v-expansion-panel-text>
            <!-- Subida de Multimedia -->
            <div class="mb-6">
              <MediaUploader ref="mediaUploader" />
            </div>

            <!-- Documentos PDF -->
            <div class="mb-6">
              <VerificationDocuments ref="documents" />
            </div>
            </v-expansion-panel-text>
            </v-expansion-panel>
            </v-expansion-panels>

            <!-- Botones -->
           <v-row class="mb-6 d-flex justify-center" align="center" style="gap: 70px;">
              <v-btn
                variant="outlined"
                class="cancel-btn"
                @click="showCancelDialog = true"
              >
                Cancelar
              </v-btn>
              <v-btn
                @click="confirm"
                class ="confirm-btn"
                :disabled="(!isFormValid || store.editedFundraising.stages.length === 0 || images.length === 0 || documentsList.length === 0)"
              >
                Confirmar
              </v-btn>
            </v-row>
          </v-form>
          <CreateStage v-if="view === 'create-stage'" @close="view = 'create-fundraising'" />
        </v-card>
      </v-col>
    </v-row>
  </v-container>

  <v-dialog v-model="showCancelDialog" max-width="400" persistent>
    <v-card class="pa-6">
      <div class="dialog-title">
        ¿Estás seguro de cancelar la creación de tu recaudación?
      </div>
      <div class="dialog-message">
        Esta acción descartará todos los cambios ingresados.
      </div>
      <v-row class="mt-6" justify="space-between">
        <v-btn class="confirm-dialog-button" @click="cancelFundraising">
          Si, confirmar
        </v-btn>
        <v-btn class="cancel-dialog-button" @click="showCancelDialog = false">
          No, volver
        </v-btn>
      </v-row>
    </v-card>
  </v-dialog>
    <v-dialog v-model="showConfirmDialog" max-width="400" persistent>
    <v-card class="pa-6">
      <div class="dialog-title">
        ¿Estás seguro de confirmar esta recaudación?
      </div>
      <div class="dialog-message">
        Esta acción guardará tu recaudación
      </div>
      <div v-if="loading" class="dialog-message">
        Estamos guardando tu recaudación. Esto puede tardar unos segundos…
      </div>
      <v-row class="mt-6" justify="space-between">
        <v-btn class="confirm-dialog-button" @click="saveFundraising" :loading="loading">
          Si, confirmar
        </v-btn>
        <v-btn class="cancel-dialog-button" @click="showConfirmDialog = false" :disabled="loading">
          No, volver
        </v-btn>
      </v-row>
    </v-card>
  </v-dialog>
</template>



<script setup lang="ts">
import { ref, reactive, onMounted, watch, computed } from 'vue'
import MediaUploader from '@/components/fundraisings/MediaUploader.vue'
import CampaignStageList from '@/components/fundraisings/CampaignStageList.vue'
import VerificationDocuments from '@/components/fundraisings/VerificationDocuments.vue'
import { SaveFundraising as saveFundraisingService } from '@/services/fundraisingService'
import { BaseFundraising, FundraisingLoadData} from '@/types/fundraisingTypes'
import { useFundraisingStore } from '@/stores/fundraisingStore'
import alertify from 'alertifyjs'
import NavBar from '@/components/general/NavBar.vue'
import CreateStage from '@/components/fundraisings/CreateStage.vue'
import { useRouter } from 'vue-router'

const formRef = ref<InstanceType<typeof HTMLFormElement> | null>(null)

const router = useRouter();
const store = useFundraisingStore();
const view = ref<'create-fundraising' | 'create-stage'>('create-fundraising')
const mediaUploader = ref<InstanceType<typeof MediaUploader> | null>(null)
const documents = ref<InstanceType<typeof VerificationDocuments> | null>(null)
const stageList = ref<InstanceType<typeof CampaignStageList> | null>(null)

const stageListError = ref(false)

const showCancelDialog = ref(false)
const showConfirmDialog = ref(false)

const loading = ref(false)

const titleRules = [
  (v: string) => !!v || 'El título es obligatorio',
  (v: string) => v.length <= 60 || 'Haz alcanzado el límite de 60 caracteres',
  (v: string) => v.length >= 10 || 'El título debe tener al menos 10 caracteres'
]

const descriptionRules = [
  (v: string) => !!v || 'La descripción es obligatoria',
  (v: string) => v.length <= 1000 || 'Haz alcanzado el límite de 1000 caracteres',
  (v: string) => v.length >= 100 || 'La descripción debe tener al menos 100 caracteres'
]

const categories = ref(store.fundraisingCategories.map(category => ({
  id: category.id,
  name: category.name
}))
)


const isFormValid = ref(false)

const confirm = () => {
  let confirmationError = false
  //Validar formulario
  if (formRef.value?.validate()) {
    isFormValid.value = true
  }
  else {
    isFormValid.value = false
    confirmationError = true
  }
    //Validar que haya al menos una etapa
  if (store.editedFundraising.stages.length === 0) {
    stageListError.value = true
    confirmationError = true
  }
  //Validar que mediaUploader sea valido
  if (mediaUploader.value && !mediaUploader.value.isValid()) {
    confirmationError = true
  }
  //Validar que documents sea valido
  if (documents.value && !documents.value.isValid()) {
    confirmationError = true
  }
  if (confirmationError) {
    alertify.error('Por favor, completa todos los campos obligatorios y corrige los errores antes de continuar.')
    return
  }
  showConfirmDialog.value = true
}
const createStage = () => {
  store.clearEditedStage()
  view.value = 'create-stage'
}
const editStage= () =>{
  view.value = 'create-stage'
}
const images = computed(() => {
  return mediaUploader.value?.previews.map(preview => preview.file).filter(file => file.type.startsWith('image/')) || []
})
const video = computed(() => {
  return mediaUploader.value?.previews.map(preview => preview.file).find(file => file.type.startsWith('video/')) || undefined
})
const documentsList = computed(() => {
  return documents.value?.pdfs.map(pdf => pdf.file) || []
})

const cancelFundraising = () => {
  store.stopEditingFundraising()
  showCancelDialog.value = false
  router.push({ name: 'home' })
}
const saveFundraising = async () => {
  loading.value = true
  //Crear un objeto BaseFundraising
  const fundraising: FundraisingLoadData = {
    title: store.editedFundraising.title,
    description: store.editedFundraising.description,
    category: store.editedFundraising.category || "1",
    //Asignar la propiedad file de todas las previews cuyo tipo sea 'image'
    images: images.value,
    video: video.value,
    stages: store.editedFundraising.stages.map(stage => ({
      ...stage,
      //Eliminar los id de las etapas para evitar conflictos al guardar
      id: undefined,
    })),
    documents: documentsList.value,
  }
  try {
    const response = await saveFundraisingService(fundraising)
    if (response.success) {
      alertify.success('¡Tu recaudación ha sido creada exitosamente!')
      store.stopEditingFundraising()
      router.push({ name: 'home' })
    } else {
      alertify.error('Ocurrió un error, vuelve a intentarlo' )
      console.error('Error al guardar la recaudación:',)
    }
  } catch (error) {
    alertify.error('Ocurrió un error, vuelve a intentarlo')
    console.error('Error al guardar la recaudación:', error)
  } finally {
    loading.value = false
    showConfirmDialog.value = false
  }
}

watch(() => store.editedFundraising.stages, (newStages) => {
  stageListError.value = newStages.length === 0
},
)

onMounted(() => {
  //store.createFundraising()
}
)
</script>

<style scoped>
.text-primary {
  color: #8f00b3 !important;
}
.text-red {
  color: #f41146 !important;
}

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
    font-size: 16px;
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

  .maxlength-message{
    color: rgb(var(--v-theme-secondary))
  }
</style>