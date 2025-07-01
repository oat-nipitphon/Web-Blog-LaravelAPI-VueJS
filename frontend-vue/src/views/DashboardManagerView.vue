<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

    <!-- Manager Blog -->
    <div class="m-auto rounded-lg bg-white shadow-lg">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-4 rounded-xl shadow text-center">
          <p class="text-3xl font-bold">Account</p>
          <RouterLink
            :to="{ name: 'ManagerReportUserProfileView' }"
            class="text-md text-blue-600"
            >Manager</RouterLink
          >
        </div>
        <div class="bg-white p-4 rounded-xl shadow text-center">
          <p class="text-3xl font-bold">Post</p>
          <RouterLink
            :to="{ name: 'ManagerReportPostsView' }"
            class="text-md text-blue-600"
            >Manager</RouterLink
          >
        </div>
        <div class="bg-white p-4 rounded-xl shadow text-center">
          <p class="text-3xl font-bold">Reward</p>
          <RouterLink
            :to="{ name: 'ManagerReportRewardView' }"
            class="text-md text-blue-600"
            >Manager</RouterLink
          >
        </div>
        <div class="bg-white p-4 rounded-xl shadow text-center">
          <p class="text-3xl font-bold text-rose-600"></p>
          <p class="text-md text-blue-600">Manager</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";
</script>

<style scoped></style>
