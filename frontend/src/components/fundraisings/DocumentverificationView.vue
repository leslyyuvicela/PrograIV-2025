<template>
  <div class="verification-documents">
    <h4 class="heading4 mb-4">Documentos de verificación</h4>

    <div v-if="documents && documents.length" class="document-grid">
      <v-row dense>
        <v-col
          v-for="(doc, index) in documents"
          :key="index"
          cols="12"
          sm="4"
          class="mb-3"
        >
          <v-card
            class="document-card"
            @click="openDialog(doc)"
            outlined
            hover
          >
            <!-- Imagen -->
            <template v-if="isImage(doc.url)">
              <v-img :src="doc.url" height="160px" cover></v-img>
            </template>

            <!-- Video -->
            <template v-else-if="isVideo(doc.url)">
              <video :src="doc.url" controls width="100%" height="160" style="object-fit: cover;" />
            </template>

            <!-- PDF -->
            <template v-else-if="isPDF(doc.url)">
            <v-card-text class="text-center d-flex flex-column align-center justify-center" style="height: 160px;">
                <img src="/img/pdf.png" alt="PDF" width="64" height="64" />
                <div class="mt-2 font-weight-medium">Ver PDF</div>
            <div class="file-name mt-1 text-truncate" style="max-width: 100%; font-size: 0.8rem;">
    {{ extractFileName(doc.url) }}
  </div>
</v-card-text>
            </template>


            <!-- Otro -->
            <template v-else>
              <v-card-text class="text-center">
                <v-icon size="48">mdi-file-question</v-icon>
                <div class="mt-2">Archivo no soportado</div>
              </v-card-text>
            </template>
          </v-card>
        </v-col>
      </v-row>
    </div>

    <div v-else>
      <p class="text-subtitle-1">No se han subido documentos de verificación.</p>
    </div>

    <!-- Diálogo de vista ampliada -->
    <v-dialog v-model="dialog" max-width="800px" persistent>
      <v-card>
        <v-card-title class="d-flex justify-between align-center">
          <span class="text-h6">Vista de archivo</span>
          <v-btn
          color="#f41146"
          class="text-white font-weight-bold ml-auto d-flex align-center justify-center"
          variant="flat"
          @click="dialog = false"
          height="36"
          min-width="90"
          style="border-radius: 14px;"
        >
          Cerrar
        </v-btn>
        </v-card-title>
        <v-card-text>
          <!-- Mostrar imagen -->
          <v-img
            v-if="selectedDoc && isImage(selectedDoc.url)"
            :src="selectedDoc.url"
            max-height="600"
            contain
          ></v-img>

          <!-- Mostrar video -->
          <video
            v-else-if="selectedDoc && isVideo(selectedDoc.url)"
            :src="selectedDoc.url"
            controls
            width="100%"
            style="max-height: 600px; object-fit: contain"
          />

          <!-- Mostrar PDF embebido -->
          <iframe
            v-else-if="selectedDoc && isPDF(selectedDoc.url)"
            :src="selectedDoc.url"
            width="100%"
            height="600px"
            style="border: none"
          ></iframe>

          <!-- Archivo no compatible -->
          <div v-else class="text-center">
            <v-icon size="48">mdi-file-question</v-icon>
            <p class="mt-2">No se puede mostrar este tipo de archivo.</p>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'DocumentVerificationView',
  props: {
    documents: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      selectedDoc: null
    }
  },
  methods: {
    isImage(url) {
      return /\.(jpeg|jpg|png|gif|webp)$/i.test(url)
    },
    isVideo(url) {
      return /\.(mp4|webm|ogg)$/i.test(url)
    },
    isPDF(url) {
      return /\.pdf$/i.test(url)
    },
    openDialog(doc) {
      this.selectedDoc = doc
      this.dialog = true
    },
    extractFileName(url) {
    try {
        const decoded = decodeURIComponent(url)
        return decoded.split('/').pop().split('?')[0]
    } catch {
        return 'Documento'
    }
    }
  }
}
</script>

<style scoped>
.document-card {
  cursor: pointer;
  transition: transform 0.2s ease;
}
.document-card:hover {
  transform: scale(1.02);
}
</style>