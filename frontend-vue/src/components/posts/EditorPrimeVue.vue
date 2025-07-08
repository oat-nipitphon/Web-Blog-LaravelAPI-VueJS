<template>
  <div class="p-4">
    <h2 class="text-2xl font-bold mb-4">PrimeVue Quill Editor</h2>
    <Editor v-model="content" style="height: 320px" />
    <div class="mt-4 p-3 border rounded">
      <h3 class="font-semibold">Output:</h3>
      <div v-html="content"></div>
    </div>
  </div>
</template>

<script setup>
import Editor from 'primevue/editor'
import { ref } from 'vue'

const content = ref('<p>Start writing here...</p>')
</script>

<style>
/* Optional: adjust the editor styling */
</style>
