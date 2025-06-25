<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
</script>
<template>
  <div class="m-auto">
    <h1>Manager Report Post View</h1>
  </div>
</template>
<!-- <script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter, RouterLink } from "vue-router";
import AdminTableReportPost from "@/components/AdminTableReportPost.vue";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import { useAdminPostStore } from "@/stores/admin.posts";
const { apiGetPosts } = usePostStore();
const posts = ref([]);
onMounted(async () => {
  posts.value = await apiGetPosts();
  console.log("admin manager post view", posts.value);
});
</script>
<template>
  <div class="container">
    <div class="w-full mt-10">
      <AdminTableReportPost />
    </div>
  </div>
</template> -->
