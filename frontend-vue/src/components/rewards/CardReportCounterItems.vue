<template>

</template>
<script setup>
const props = defineProps({
  items: {
    type: Array,
    required: true,
  }
});

console.log('card report counter items', props.items);

</script>