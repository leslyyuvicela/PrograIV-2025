<template>
  <v-card class="pa-4" elevation="1" :class="{ 'error-border': hasError }">
    <v-row class="mb-3">
      <v-col cols="12" class="text-left">
        <h3 class="fundraising-label text-left">Multimedia</h3>
        <p class="description-paragraph" style="font-size: 13px;">
          Puedes subir hasta 3 imágenes y un video que representen tu necesidad.
        </p>
      </v-col>

      <!-- Previews -->
      <v-col
        v-for="(file, index) in previews"
        :key="index"
        cols="6"
        md="2"
        class="d-flex flex-column align-center mb-4 position-relative"
      >
        <v-img
          v-if="file.type.startsWith('image/')"
          :src="file.url"
          height="100"
          width="100"
          class="rounded"
          cover
          style="cursor: pointer;"
          @click="openPreview(file)"
        ></v-img>
        <video
          v-else
          :src="file.url"
          height="100"
          width="100"
          class="rounded"
          style="cursor: pointer;"
          controls
          @click="openPreview(file)"
        ></video>
        <button class="delete-btn hover-grow-large" @click="removeFile(index)">
          <Icon icon="material-symbols:close-rounded" width="20" height="20" />
        </button>
      </v-col>

      <!-- Botón para subir archivo -->
      <v-col cols="12" class="text-center">
        <div v-if="canAddMoreFiles">
          <div v-if="previews.length === 0">
            <v-btn class="add-file-btn" variant="flat" @click="triggerFileInput">
              Subir archivo
            </v-btn>
          </div>
          <div v-else class="d-flex justify-end">
            <button class="add-btn" @click="triggerFileInput">
              <Icon icon="material-symbols:add-rounded" width="24" height="24" />
            </button>
          </div>
        </div>
        <input
          ref="fileInput"
          type="file"
          class="d-none"
          multiple
          accept="image/png, image/jpeg, image/jpg, video/mp4"
          @change="onFileChange"
        />
      </v-col>

      <!-- Mensaje de error -->
      <v-col cols="12" v-if="hasError">
        <p class="text-error text-caption mt-2">
          {{errorMessage}}
        </p>
      </v-col>
    </v-row>
  </v-card>
<div v-if="selectedFile">
  <div class="preview-overlay" @click="closePreview"></div>
  <div class="preview-modal">
    <button class="close-btn hover-grow" @click="closePreview">Cerrar</button>
    <img
      v-if="selectedFile.type.startsWith('image/')"
      :src="selectedFile.url"
      width="800"
      height="600"
      class="expanded-media"
    />
    <video
      v-else
      :src="selectedFile.url"
      width="800"
      height="600"
      controls
      class="expanded-media"
    ></video>
  </div>
</div>


</template>

<script setup lang="ts">
import { ref, computed, defineProps, defineExpose, watch } from 'vue'
import { useFundraisingStore } from '@/stores/fundraisingStore'
import { Icon } from '@iconify/vue'
import alertify from 'alertifyjs'



interface MediaPreview {
  file: File
  url: string
  type: string
}

const store = useFundraisingStore()

const previews = ref<MediaPreview[]>([])
const hasError = ref(false)
const fileInput = ref<HTMLInputElement | null>(null)
const errorMessage = ref('Este campo es obligatorio. Debes subir al menos una imagen o un video.')

const canAddMoreFiles = computed(() => {
  const images = previews.value.filter(f => f.type.startsWith('image/')).length
  const videos = previews.value.filter(f => f.type.startsWith('video/')).length
  return images < 3 || videos < 1
})

function triggerFileInput() {
  fileInput.value?.click()
}


const selectedFile = ref<MediaPreview | null>(null)

function openPreview(file: MediaPreview) {
  selectedFile.value = file
}

function closePreview() {
  selectedFile.value = null
}



function onFileChange(event: Event) {
  const input = event.target as HTMLInputElement
  if (!input.files) return
  handleFiles(Array.from(input.files))
  input.value = ''
}

function handleFiles(files: File[]) {
  errorMessage.value = ''
  hasError.value = false
  for (const file of files) {
    const currentImages = previews.value.filter(f => f.type.startsWith('image/')).length
    const currentVideos = previews.value.filter(f => f.type.startsWith('video/')).length

    //Validar tamaño máximo de archivo (100 MB)
    if (file.size > 100 * 1024 * 1024) {
      errorMessage.value = 'Tu archivo no debe exceder los 100 MB'
      hasError.value = true
      continue
    }

    if (file.type.startsWith('image/')) {
      if (currentImages >= 3) {
        errorMessage.value = 'Solo puedes subir hasta 3 imágenes.'
        hasError.value = true
        continue
      }
    } else if (file.type === 'video/mp4') {
      if (currentVideos >= 1) {
        errorMessage.value = 'Solo puedes subir un video.'
        hasError.value = true
        continue
      }
    } else {
      errorMessage.value = 'El tipo de archivo que intentas cargar no es válido.'
      hasError.value = true
      continue
    }

    const url = URL.createObjectURL(file)
    previews.value.push({ file, url, type: file.type })
    hasError.value = false
  }
}

function removeFile(index: number) {
  const item = previews.value[index]
  URL.revokeObjectURL(item.url)
  previews.value.splice(index, 1)
}

function isValid() {
  hasError.value = previews.value.length === 0
  if (hasError.value) {
    errorMessage.value = 'Este campo es obligatorio. Debes subir al menos una imagen o un video.'
  } else {
    errorMessage.value = ''
  }
  return !hasError.value
}

defineExpose({
  previews,
  isValid,
})


//Watch para ver si hay al menos una imagen
watch(previews.value, (newPreviews) => {
  const hasImage = newPreviews.some(p => p.type.startsWith('image/'))
  hasError.value = !hasImage
}, 

)
</script>

<style scoped>
.fundraising-label {
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  color: rgb(var(--v-theme-text));
  font-weight: bold;
}

.description-paragraph {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  color: rgb(var(--v-theme-text));
}

.add-file-btn {
  background-color: rgb(var(--v-theme-primary));
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  color: white;
  border-radius: 8px;
  padding: 0.5rem 1.5rem;
}

.rounded {
  border-radius: 12px;
}

.delete-btn {
  margin-left: 8px;
  position: absolute;
  top: -4px;
  right: -6px;
  background-color: white;
  border: none;
  border-radius: 50%;
  padding: 4px 4px;
  cursor: pointer;
  font-size: 15px;
  color: rgb(var(--v-theme-secondary));
}

.add-btn {
  border: 2px solid #8e44ad;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: white;
  color: #8e44ad;
  font-size: 20px;
  cursor: pointer;
}

.add-btn:focus,
.delete-btn:focus,
.v-btn:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(142, 68, 173, 0.3);
}

.delete-btn:focus,
.delete-btn:active {
  box-shadow: none !important;
  outline: none !important;
  border: none !important;
}

.error-border {
  border: 1.5px solid #f44336 !important;
  border-radius: 12px;
}

.preview-modal {
  position: fixed;
  top: 58%;
  left: 50%;
  width: 90%;
  height: 90%;
  max-width: 800px;
  max-height: 600px;
  transform: translate(-50%, -50%);
  background-color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 16px;
  border-radius: 12px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
  flex-direction: column;
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  font-family: 'Poppins', sans-serif;
  font-size: 20px;
  color: #ffffff;
  background-color: #f41146;
  width: 100px;
  height: 50px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  z-index: 10000;
}

.expanded-media {
  border-radius: 8px;
  object-fit: contain;
}
.preview-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 9998;
}
.preview-modal img, .preview-modal video {
  max-width: 100%;
  max-height: 100%;
  border-radius: 8px;
}



</style>
