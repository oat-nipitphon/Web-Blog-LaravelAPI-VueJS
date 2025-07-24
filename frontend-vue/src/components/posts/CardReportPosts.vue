<template>
  <!-- transition hover:shadow-lg m-auto dark:bg-gray-800 dark:border-gray-700  p-2 rounded-lg shadow-lg -->
  <article class="w-max-full p-3">
    <div class="bg-white border rounded-lg shadow-md p-2 group relative block">
      <div
        v-for="(image, index) in post?.postImage"
        :key="index"
        class="relative "
      >
        <!-- รูปภาพ -->
        <img
          class="w-full h-45 rounded-sm transition-transform duration-300 hover:scale-105 object-cover"
          :src="
            image?.imageData
              ? 'data:image/png;base64,' + image?.imageData
              : imagePostDefault
          "
          alt="PostImage"
        />

        <div class="grid grid-cols-2">
          <div class="flex justify-start items-start">
            <div
              class="absolute top-2 rounded-full shadow-lg bg-blue-400 px-2 ml-2"
            >
              <span class="text-sm font-medium text-white">
                {{ post?.postType?.typeName }}
              </span>
            </div>
          </div>
          <div class="flex justify-end items-end">
            <div class="absolute top-2 bg-white rounded-md shadow-lg">
              <card-dropdown-event
                :post="post"
                :on-store-post="onStorePost"
                :on-edit-post="onEditPost"
                :on-delete-post="onDeletePost"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-2">
      <!-- px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0 -->
      <div class="grid grid-row">
        <h3 class="text-lg font-bold text-gray-800 tracking-tight mt-3">
          {{ post.postTitle }}
        </h3>
        <p class="text-gray-500 p-1 ml-2">
          {{ formatDateTime.formatDate(post?.postCreatedAT) }}
        </p>
      </div>
      <div class="flex justify-between">
        <div class="m-auto">
          <card-event-pop
            :on-event-post-pop="onEventPostPop"
            :post="post"
            :auth-profile-i-d="authStore.users?.userProfile?.id"
          />
        </div>
      </div>
    </div>
    <div class="pt-2 px-2 pl-2 pb-2 sm:px-10 sm:pt-10 sm:pb-0">
      <p class="line-clamp-3 text-md/relaxed text-gray-500 mt-1 mb-3">
        {{ post?.postContent }}
      </p>
      <p class="text-md/relaxed text-blue-500 font-bold">
        <RouterLink
          :to="{
            name: 'ShowDetailPostView',
            params: {
              id: post?.postID,
            },
          }"
        >
          See move..
        </RouterLink>
      </p>
    </div>

    <div class="grid grid-cols-2 mt-2">
      <card-profile :user="post?.user" :profile="post?.userProfile" />
      <div></div>
    </div>
  </article>
</template>

<script setup>
defineProps({
  post: {
    type: Object,
    required: true,
  },
});

import { ref } from "vue";
import { useRouter, RouterLink } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";

import imagePostDefault from "@/assets/images/keyboard.jpg";

import CardDropdownEvent from "@/components/posts/CardDropdownEvent.vue";
import CardEventPop from "@/components/posts/CardEventPop.vue";

import CardProfile from "@/components/user-profiles/CardProfile.vue";

const router = useRouter();
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const {
  storeGetPosts,
  storeDeletePost,
  storeEventPostPop,
  storeConfirmStorePost,
} = usePostStore();

const onStorePost = async (postID) => {
  await storeConfirmStorePost(postID);
  await storeGetPosts();
};

const onEditPost = (postID) => {
  console.log("function on edit post ", postID);
  router.push({
    name: "EditPostView",
    params: {
      id: postID,
    },
  });
};

const onDeletePost = async (postID) => {
  console.log("on delete post ", postID);
  const success = await storeDeletePost(postID);
  if (success) {
    await storeGetPosts();
  }
};

const onEventPostPop = async (postID, profileID, status) => {
  await storeEventPostPop(postID, profileID, status);
};

const formatDateTime = {
  formatDate(dateTime) {
    if (!dateTime) return;
    const date = new Date(dateTime);
    const year = date.getFullYear() + 543;
    const month = date.getMonth();
    const day = date.getDate();

    const thaiMonths = [
      "มกราคม",
      "กุมภาพันธ์",
      "มีนาคม",
      "เมษายน",
      "พฤษภาคม",
      "มิถุนายน",
      "กรกฎาคม",
      "สิงหาคม",
      "กันยายน",
      "ตุลาคม",
      "พฤศจิกายน",
      "ธันวาคม",
    ];

    return `${day} ${thaiMonths[month]} ${year}`;
  },
  formatTime(dateTime) {
    if (!dateTime) return;

    const date = new Date(dateTime);
    const hour = date.getHours().toString().padStart(2, "0");
    const minute = date.getMinutes().toString().padStart(2, "0");
    const second = date.getSeconds().toString().padStart(2, "0");

    return `เวลา ${hour}:${minute}:${second} น.`;
  },
};
</script>
