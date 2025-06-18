<template>
  <div class="media-gallery">
    <!-- Contenedor principal -->
    <div class="main-media">
      <video
        v-if="isVideo(currentMedia)"
        controls
        class="media-display"
      >
        <source :src="currentMedia" type="video/mp4" />
        Tu navegador no soporta el elemento de video.
      </video>
      <v-img
        v-else
        :src="currentMedia"
        class="media-display"
        cover
      ></v-img>
    </div>

    <!-- Miniaturas -->
    <div class="thumbnails">
      <div
        v-for="(media, index) in mediaList"
        :key="index"
        class="thumbnail"
        :class="{ active: currentMedia === media }"
        @click="currentMedia = media"
      >
        <video v-if="isVideo(media)" muted>
          <source :src="media" type="video/mp4" />
        </video>
        <v-img
          v-else
          :src="media"
          cover
        ></v-img>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MediaGallery',
  props: {
    mediaList: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentMedia: this.mediaList[0]
    }
  },
  methods: {
    isVideo(media) {
      return media.endsWith('.mp4') || media.includes('video');
    }
  }
}
</script>

<style scoped>
.media-gallery {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.main-media {
  width: 100%;
  max-width: 700px;
  height: 400px;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 16px;
}
.media-display {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}
.thumbnails {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: center;
}
.thumbnail {
  width: 80px;
  height: 60px;
  cursor: pointer;
  border: 2px solid transparent;
  border-radius: 4px;
  overflow: hidden;
}
.thumbnail.active {
  border-color: #E987FF;
}
.thumbnail video,
.thumbnail .v-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>