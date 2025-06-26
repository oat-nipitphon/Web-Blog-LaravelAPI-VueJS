<template>
    <div class="m-auto">
        <h1> Dashboard Manager </h1>
        <h3>Welcome {{ authStore?.users?.username }}</h3>
    </div>
</template>
<script setup>
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
console.log('dashboard manager view ', authStore?.users?.username);

</script>