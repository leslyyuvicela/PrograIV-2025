<template>
  <v-container fluid class="campaign-details py-5">
    <!-- Título -->
    <h1 class="heading1 mb-4 text-left text-gray">{{ campaign.title }}</h1>

    <!-- Progreso y creador -->
    <div class="d-flex flex-column flex-sm-row align-center justify-space-between mb-3">
      <v-progress-linear
        :model-value="campaign.progress"
        height="10"
        color="#E987FF"
        rounded
        class="flex-grow-1 mr-sm-4 mb-2 mb-sm-0"
      ></v-progress-linear>
      <span class="paragraph1 text-gray">{{ campaign.progress }}% completado</span>
    </div>
    <p class="paragraph2 mb-5 text-left text-gray">Creada por <strong>{{ campaign.creator }}</strong></p>

    <!-- Galería -->
    <div class="gallery mb-6">
      <MediaGallery :mediaList="campaign.media" />
    </div>

    <!-- Descripción y Categoría -->
    <div class="mb-6 description-category">
      <h4 class="heading4 mb-2 text-left text-gray">Descripción</h4>
      <p class="paragraph1 text-gray">{{ campaign.description }}</p>
      <p class="paragraph2 mt-3 text-gray"><strong>Categoría:</strong> {{ campaign.category }}</p>
    </div>

    <!-- Etapas -->
    <div class="stages mb-6">
      <h4 class="heading4 mb-2 text-left text-gray">Etapas</h4>
      <v-timeline align-top dense direction="horizontal" class="timeline">
        <v-timeline-item
          v-for="(stage, index) in campaign.stages"
          :key="index"
          :color="selectedStageIndex === index ? '#E987FF' : 'grey'"
          fill-dot
          @click="selectStage(index)"
          class="timeline-item"
        >
          <div class="text-center text-gray">
            <div class="text-subtitle-2 font-weight-medium mb-1">
              {{ stage.title }}
            </div>
            <div class="text-caption grey--text">
              {{ stage.goal }}
            </div>
          </div>
        </v-timeline-item>
      </v-timeline>
    </div>

    <!-- Detalles de etapa -->
    <div class="stage-details mb-6">
      <h4 class="heading4 mb-3 text-left text-gray">Detalles de la etapa</h4>
      <div v-if="selectedStage" class="text-left text-gray">
        <p><strong>Nombre:</strong> {{ selectedStage.title }}</p>
        <p><strong>Descripción:</strong> {{ selectedStage.description }}</p>
        <p><strong>Tipo de donación:</strong> {{ selectedStage.type }}</p>
        <p><strong>Meta:</strong> {{ selectedStage.goal }}</p>
        <p><strong>Fecha:</strong> {{ selectedStage.date }}</p>
      </div>
      <div v-else class="text-left text-gray">
        <p class="text-subtitle-1">Selecciona una etapa para ver los detalles.</p>
      </div>
    </div>

    <!-- Documentos -->
    <div class="verification-documents mb-6">
      <DocumentVerificationView :documents="campaign.documents" />
    </div>

    <!-- Chat Donantes -->
    <div v-if="showChat" class="chat-floating-box">
      <h4 class="heading4 mb-2 text-left text-gray">Chat entre Donantes</h4>
      <v-btn icon small @click="showChat = false">
      <Icon icon="mdi:close" width="20" height="20" />
  </v-btn>
      <div class="chat-window">
        <div
          v-for="(msg, index) in messages"
          :key="index"
          :class="['chat-message', msg.sender === donorName ? 'mine' : 'other']"
        >
          <strong>{{ msg.sender }}:</strong> {{ msg.text }}
        </div>
      </div>
      <div class="d-flex mt-3">
        <input
          v-model="newMessage"
          @keyup.enter="sendMessage"
          class="form-control mr-2"
          placeholder="Escribe un mensaje..."
        />
        <v-btn color="#E987FF" class="white--text" @click="sendMessage">Enviar</v-btn>
      </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex justify-center justify-sm-end flex-wrap mt-8">
      <v-btn icon class="mr-2" @click="openChat">
        <Icon icon="mdi:chat-outline" width="24" height="24" class="text-gray" />
      </v-btn>
      <v-btn icon class="mr-4" @click="reportCampaign">
        <Icon icon="mdi:alert-circle-outline" width="24" height="24" class="text-gray" />
      </v-btn>
      <v-btn color="#E987FF" class="white--text text-capitalize px-6" @click="donate">
        <Icon icon="mdi:heart-outline" class="mr-2" />
        Donar
      </v-btn>
    </div>
  </v-container>
</template>

<script>
import { Icon } from '@iconify/vue'
import MediaGallery from '../components/fundraisings/MediaGallery.vue'
import DocumentVerificationView from '../components/fundraisings/DocumentverificationView.vue'
//import io from 'socket.io-client'

export default {
  name: 'CampaignDetails',
  components: {
    MediaGallery,
    DocumentVerificationView,
    Icon
  },
  data() {
    return {
      selectedStageIndex: null,
      showChat: false,
      donorName: 'Donante_' + Math.floor(Math.random() * 1000),
      newMessage: '',
      messages: [],
      socket: null,
      campaign: {
        title: 'Campaña de ejemplo',
        progress: 45,
        creator: 'UsuarioX',
        description: 'Esta es una descripción breve de la campaña.',
        category: 'Educación',
        media: [
          'https://picsum.photos/seed/picsum/800/600',
          'https://picsum.photos/id/237/800/600',
          'https://www.w3schools.com/html/mov_bbb.mp4'
        ],
        stages: [
          {
            title: 'Meta 1',
            description: 'Recolectar dinero para materiales de construcción.',
            date: '2025-05-10',
            type: 'monetaria',
            goal: '$5000'
          },
          {
            title: 'Meta 2',
            description: 'Reclutar personal para construcción.',
            date: '2025-06-01',
            type: 'servicios',
            goal: '5 voluntarios'
          },
          {
            title: 'Meta 3',
            description: 'Recolectar escritorios y sillas.',
            date: '2025-07-01',
            type: 'bienes',
            goal: '20 escritorios'
          }
        ],
        documents: [
          { url: 'https://www.asamblea.gob.sv/sites/default/files/documents/decretos/A0404128-0A8F-4993-9AC8-537F44347CA3.pdf' },
          { url: 'https://3.bp.blogspot.com/-pBFDPrX09nQ/U_eke0zJktI/AAAAAAACR50/ImINRN8uftI/s1600/cute-ladybird-1920x1200-wallpaper.jpg' },
          { url: 'https://www.w3schools.com/html/mov_bbb.mp4' }
        ]
      }
    }
  },
  computed: {
    selectedStage() {
      return this.selectedStageIndex !== null ? this.campaign.stages[this.selectedStageIndex] : null
    }
  },
  methods: {
    selectStage(index) {
      this.selectedStageIndex = index
    },
    openChat() {
      this.showChat = !this.showChat
    },
    reportCampaign() {
      console.log('Reportando campaña...')
    },
    donate() {
      console.log('Donando...')
    },
    sendMessage() {
      if (this.newMessage.trim()) {
        this.socket.emit('donationMessage', {
          sender: this.donorName,
          text: this.newMessage
        })
        this.newMessage = ''
      }
    }
  },
  mounted() {
    /*
    this.socket = io('http://localhost:3000')
    this.socket.on('donationMessage', (msg) => {
      this.messages.push(msg)
    })
      */
  }
}
</script>

<style scoped>
.campaign-details {
  max-width: 960px;
  margin: 0 auto;
  font-family: inherit;
}

.text-left {
  text-align: left;
}

.text-gray {
  color: #676269 !important;
}

.description-category p {
  text-align: left;
}


.gallery {
  background-color: #f9f9f9;
  min-height: 250px;
  border-radius: 12px;
  padding: 1rem;
  border: 1px solid #e0e0e0;
}

.stage-details,
.verification-documents,
.chat-box {
  background-color: #f8f8f8;
  padding: 1.2rem 1.5rem;
  border-radius: 12px;
  border: 1px solid #ddd;
  transition: all 0.3s ease;
  margin-top: 2rem;
}

.stage-details:hover,
.verification-documents:hover,
.chat-box:hover {
  background-color: #f0eef0;
}

.timeline {
  overflow-x: auto;
  white-space: nowrap;
  padding-bottom: 1rem;
  margin-top: 0.5rem;
  padding-left: 0.5rem;
}

.timeline-item {
  cursor: pointer;
  min-width: 180px;
  transition: transform 0.2s;
}

.timeline-item:hover {
  transform: scale(1.05);
}

.v-btn {
  border-radius: 12px !important;
}

.v-btn .v-icon,
.v-btn .iconify {
  color: #676269;
}

.v-btn:last-child {
  font-weight: 600;
  text-transform: none;
  padding: 0 1.5rem;
  height: 44px;
}

.chat-floating-box {
  position: fixed;
  bottom: 90px;
  right: 30px;
  width: 320px;
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  border-radius: 12px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  padding: 1rem;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  max-height: 500px;
}

.chat-floating-box .chat-window {
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #ccc;
  padding: 0.5rem;
  border-radius: 8px;
  background-color: #fff;
  margin-bottom: 0.5rem;
}


.chat-message {
  margin-bottom: 0.5rem;
  padding: 0.4rem;
  border-radius: 8px;
  max-width: 80%;
  word-wrap: break-word;
}

.chat-message.mine {
  background-color: #e9caff;
  align-self: flex-end;
  text-align: right;
}

.chat-message.other {
  background-color: #e0e0e0;
  text-align: left;
}
</style>
