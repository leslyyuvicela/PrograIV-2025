<template>
  <v-timeline align-top dense class="timeline" direction="horizontal">
    <v-timeline-item
      v-for="(stage, index) in stages"
      :key="index"
      :color="selectedIndex === index ? '#73D5F5' : 'blue'"
      fill-dot
      @click="selectStage(index)"
      class="timeline-item"
    >
      <div class="text-center">
        <div class="text-subtitle-2 font-weight-medium mb-1">
          {{ stage.title }}
        </div>
        <div class="text-caption grey--text">
          {{ stage.moneyGoal }}
        </div>
      </div>
    </v-timeline-item>
  </v-timeline>
</template>

<script>
export default {
  name: 'StageTimeline',
  props: {
    stages: Array,
    selectedIndex: Number
  },
  emits: ['select'],
  methods: {
    selectStage(index) {
      this.$emit('select', index)
    }
  }
}
</script>

<style scoped>
.timeline {
  overflow-x: auto;
  white-space: nowrap;
  padding-bottom: 1rem;
}
.timeline-item {
  cursor: pointer;
  min-width: 160px;
}
.fundraising-label {
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  color: rgb(var(--v-theme-text));
  font-weight: bold;
}
</style>