<template>
  <div
    v-if="storePosts"
    class="grid max-w-screen-xl mx-auto px-4 gap-5 m-2 grid-cols-1 sm:grid-cols-1 md:grid-cols-2"
  >
    <div
      class="aspect-square w-full p-2 rounded-lg shadow-lg transition hover:shadow-xl bg-white dark:bg-gray-800 dark:border dark:border-gray-700"
      v-for="post in storePosts"
      :key="post.id"
    >
      <card-profile :user="post?.user" :profile="post?.userProfile" />
      <card-report-posts :post="post" />
    </div>
  </div>

  <div
    v-else
    class="m-auto p-3 bg-red-500 flex justify-center items-center rounded-lg shadow text-center"
  >
    <p class="text-3xl font-bold text-white">Post require false !!</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { usePostStore } from "@/stores/post";
import CardProfile from "@/components/user-profiles/CardProfile.vue";
import CardReportPosts from "@/components/posts/CardReportPosts.vue";

const router = useRouter();

const postStore = usePostStore();
const { storePosts } = storeToRefs(postStore);

onMounted(async () => {
  await postStore.storeGetPosts();
});
</script>
