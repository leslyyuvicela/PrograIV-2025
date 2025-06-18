<template>
  <v-card class="pa-4" elevation="1" :class="{ 'border-error': validationError }">
    <v-row class="mb-3">
      <!-- Etiqueta -->
      <v-col cols="12" class="text-left">
        <h3 class="fundraising-label">
          Requisito de verificación
        </h3>
        <p class="description-paragraph" style="font-size: 13px;">
          Sube archivos que acrediten tu necesidad. No incluyas datos sensibles. (.png, .jpg, .jpeg, .mp4, .pdf)
        </p>
      </v-col>

      <!-- Archivos subidos -->
      <v-col
        v-for="(file, index) in pdfs"
        :key="index"
        cols="12"
        md="4"
        class="mb-2"
      >
        <div class="file-item d-flex align-center justify-between border rounded px-3 py-2">
          <div class="d-flex align-center" style="gap: 10px;">
            <div @click="openPreview(file)" class="cursor-pointer">
              <v-img
                v-if="file.type.startsWith('image/')"
                :src="file.url"
                width="40"
                height="40"
                cover
                class="rounded"
              />
              <video
                v-else-if="file.type.startsWith('video/')"
                :src="file.url"
                width="40"
                height="40"
                muted
                class="rounded"
              ></video>
              <Icon
                v-else
                icon="mdi:file-pdf-box"
                class="text-red"
                width="32"
              />
            </div>
            <span class="file-name text-truncate">{{ file.file.name }}</span>
          </div>
          <button class="delete-btn hover-grow-large" @click="removeFile(index)">
            <Icon  width="20" icon="mdi:close" style="color: #F41146" />
          </button>
        </div>
      </v-col>

      <!-- Botón para subir archivo -->
      <v-col cols="12" class="text-center">
        <div v-if="pdfs.length === 0">
          <v-btn
            class="action-button"
            variant="flat"
            @click="triggerFileInput"
          >
            Subir archivo
          </v-btn>
        </div>
        <div v-else class="d-flex justify-end">
          <button class="add-btn" @click="triggerFileInput">
           <Icon icon="material-symbols:add-rounded" width="24" height="24" />
          </button>
        </div>
        <input
          ref="fileInput"
          type="file"
          class="d-none"
          multiple
          accept="image/png, image/jpeg, image/jpg, video/mp4, application/pdf"
          @change="handleFiles($event.target.files)"
        />

        <!-- Mensaje de error -->
        <v-col cols="12" v-if="validationError" class="mt-3 text-center">
          <p class="text-error text-caption">
            {{errorMessage}}
          </p>
        </v-col>
      </v-col>
    </v-row>
    
    <!-- Modal de vista previa -->
  </v-card>
  <div v-if="previewFile">
    <div class="preview-overlay" @click="closePreview"></div>
    <div class="preview-modal">
      <v-row>
        <v-col cols="10" class="mr-5">
                <img
        v-if="previewFile.type.startsWith('image/')"
        :src="previewFile.url"
        width="800"
        height="600"
        class="expanded-media"
      />
      <video
        v-else-if="previewFile.type.startsWith('video/')"
        :src="previewFile.url"
        width="800"
        height="600"
        controls
        class="expanded-media"
      ></video>
      <!--Previsualizar un documento-->
      <div v-else>
        <iframe
          :src="previewFile.url"
          width="700"
          height="480"
          class="expanded-media"
          frameborder="0"
        ></iframe>
      </div>
        </v-col>
        <v-col cols="2" class="text-right ml-5">
        <button class="close-btn hover-grow-large" @click="closePreview">Cerrar</button>
    </v-col>

      </v-row>

    </div>
  </div>
  
</template>

<script setup lang="ts">
import { ref, defineExpose, watch } from 'vue'
import { Icon } from '@iconify/vue'
import alertify from 'alertifyjs'

interface PreviewFile {
  file: File
  url: string
  type: string
}

const pdfs = ref<PreviewFile[]>([])
const validationError = ref(false)
const fileInput = ref<HTMLInputElement | null>(null)
const previewFile = ref<PreviewFile | null>(null)
const errorMessage = ref('Este campo es obligatorio. Por favor sube al menos un archivo.')

function triggerFileInput() {
  fileInput.value?.click()
}

function handleFiles(fileList: FileList | null) {
  if (!fileList) return
  const files = Array.from(fileList)

  if ((pdfs.value.length + files.length) > 5) {
    errorMessage.value = 'Solo puedes subir hasta 5 archivos.'
    validationError.value = true
    return
  }

  for (const file of files) {
    if (!['image/png', 'image/jpeg', 'image/jpg', 'video/mp4', 'application/pdf'].includes(file.type)) {
      errorMessage.value = 'Solo se permiten imágenes, videos o PDFs.'
      validationError.value = true
      continue
    }
    //Verificar tamaño del archivo
    if (file.size > 100 * 1024 * 1024) { // 100 MB
      errorMessage.value = 'El archivo no puede ser mayor a 100 MB.'
      validationError.value = true
      continue
    }

    const url = URL.createObjectURL(file)
    pdfs.value.push({ file, url, type: file.type })
    validationError.value = false
  }
}

function removeFile(index: number) {
  URL.revokeObjectURL(pdfs.value[index].url)
  pdfs.value.splice(index, 1)
}

function isValid() {
  validationError.value = pdfs.value.length === 0
  if (validationError.value) {
    errorMessage.value = 'Este campo es obligatorio. Debes subir al menos un archivo.'
  } else {
    errorMessage.value = ''
  }
  return !validationError.value
}

function openPreview(file: PreviewFile) {
  previewFile.value = file
}

function closePreview() {
  previewFile.value = null
}
defineExpose({
  pdfs,
  isValid
})

watch(pdfs.value, (newFiles) => {
  validationError.value = newFiles.length === 0
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
.delete-btn {
  background-color: transparent;
  border: none;
  border-radius: 50%;
  padding: 4px;
  cursor: pointer;
  font-size: 15px;
  color: rgb(var(--v-theme-secondary));
  flex-shrink: 0;
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
.file-item {
  min-height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-sizing: border-box;
  overflow: hidden;
  position: relative;
}
.file-name {
  max-width: 130px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  font-size: 14px;
  color: #333;
}
.border-error {
  border: 1.5px solid #f44336 !important;
  border-radius: 8px;
}
.preview-modal {
  position: fixed;
  top: 53%;
  left: 50%;
  width: 90%;
  height: 90%;
  max-width: 800px;
  max-height: 480px;
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
.preview-modal img, .preview-modal video,  .preview-modal iframe {
  max-width: 100%;
  max-height: 100%;
  border-radius: 8px;
}
.action-button {
  background-color: rgb(var(--v-theme-primary));
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  border-radius: 8px;
  color: white;
  padding: 0.5rem 1.5rem;
}

</style>
