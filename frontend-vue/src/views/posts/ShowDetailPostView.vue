<template>
    <div>
        
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { usePostStore } from '@/stores/post'
import PageHeader from '@/components/PageHeader.vue';

const postStore = usePostStore();
const {  } = storeToRefs(postStore);

</script>