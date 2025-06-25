<script setup>
import { ref, onMounted, computed } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import PageHeader from "@/components/PageHeader.vue";
import CartShowContentHome from "@/components/CardReportPost.vue";
import CardReportPost from "@/components/CardReportPost.vue";

const router = useRouter();
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
const postStore = usePostStore();
const { storePosts } = storeToRefs(postStore);

onMounted(async () => {
  await postStore.storeGetPosts();
});

</script>
<template>
    <div class="grid grid-cols-2">
      <PageHeader title="HomeView" />
      <div class="flex justify-end">
        <RouterLink
        class="block border border-gray-900 bg-blue-500 py-2 px-3.5 font-medium text-white mt-auto mb-auto mr-5"
        :to="{ name: 'CreatePostView' }"
        >
        Create Post
        </RouterLink>
      </div>
    </div>
    <div
      v-if="storePosts"
      class="grid max-w-screen-xl mx-auto px-4 grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 m-2"
    >
      <CardReportPost
        v-for="post in storePosts"
        :key="post.id"
        :post="post"
      />
    </div>
    <div v-else class="m-auto p-3 bg-red-500 flex justify-center">
      <p class="text-3xl font-blod text-white">Post require false !!</p>
    </div>
</template>
