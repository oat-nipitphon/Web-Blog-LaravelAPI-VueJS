<template>
  <div
    v-if="storePosts"
    class="grid max-w-screen-xl mx-auto px-4 grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 m-2"
  >
    <div class="w-max-full p-2 rounded-lg shadow-lg transition hover:shadow-lg m-auto dark:bg-gray-800 dark:border-gray-700"
     v-for="post in storePosts" :key="post.id">
      <CardProfile
        :user="post?.user"
        :profile="post?.userProfile"
        :auth-profile-id="authStore?.users?.userProfile?.id"
      />
      <CardReportPost :post="post" />
</div>
  </div>
  <div v-else class="m-auto p-3 bg-red-500 flex justify-center">
    <p class="text-3xl font-blod text-white">Post require false !!</p>
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";

import CardProfile from "@/components/user-profiles/CardProfile.vue";
import CardReportPost from "@/components/posts/CardReportPost.vue";

const router = useRouter();
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
const postStore = usePostStore();
const { storePosts } = storeToRefs(postStore);

onMounted(async () => {
  await postStore.storeGetPosts();
});
</script>