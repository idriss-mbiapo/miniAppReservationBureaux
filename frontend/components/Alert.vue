<template>
    <div v-if="visible" :class="`alert alert-${type}`" role="alert">
      {{ message }}
    </div>
</template>
  
<script setup lang="ts">
import { ref, watchEffect, defineProps } from 'vue'

const props = defineProps<{
    type: 'success' | 'danger' | 'warning' | 'info',
    message: string
}>()
  
const visible = ref(true)
  
watchEffect(() => {
   setTimeout(() => (visible.value = false), 4000)
})
</script>