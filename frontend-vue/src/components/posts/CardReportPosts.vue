<template>
  <!-- transition hover:shadow-lg m-auto dark:bg-gray-800 dark:border-gray-700  p-2 rounded-lg shadow-lg -->
  <article class="w-max-full p-3">
    <div class="bg-white border rounded-lg shadow-md p-2 group relative block">
      <div
        v-for="(image, index) in post?.postImage"
        :key="index"
        class="relative"
      >
        <!-- รูปภาพ -->
        <img
          :src="
            image?.imageData
              ? 'data:image/png;base64,' + image?.imageData
              : `${imagePostDefault}`
          "
          alt=""
          class="h-56 w-full object-cover rounded-lg"
        />

        <!-- Dropdown -->
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

    <!-- <div class="grid grid-cols-[auto_10%]">
      <div class="flex justify-start items-start">
        <div class="grid grid-rows-2 mt-2">
          <h3
            class="relative z-10 rounded-full px-3 py-1.5 font-medium text-gray-800"
          >
            {{ post?.postTitle }}
          </h3>
          <p class="font-medium text-gray-600 mr-2 px-3 py-1.5">
            {{ formatDateTime(post?.postCreatedAT) }}
          </p>
        </div>
      </div>
    </div> -->

    <article
      class="rounded-[10px] border border-gray-200 bg-white px-4 pt-12 pb-4"
    >
      <time datetime="2022-10-10" class="block text-xs text-gray-500">
        {{ formatDateTime.formatDate(post?.postCreatedAT) }}
      </time>

      <a href="#">
        <h3 class="mt-0.5 text-lg font-medium text-gray-900">
          How to center an element using JavaScript and jQuery
        </h3>
      </a>

      <div class="mt-4 flex flex-wrap gap-1">
        <span
          class="rounded-full bg-purple-100 px-2.5 py-0.5 text-xs whitespace-nowrap text-purple-600"
        >
          Snippet
        </span>

        <span
          class="rounded-full bg-purple-100 px-2.5 py-0.5 text-xs whitespace-nowrap text-purple-600"
        >
          JavaScript
        </span>
      </div>
    </article>

    <!-- Post Content -->
    <div class="rounded-lg shadow-md p-2">
      <p class="line-clamp-3 text-md/relaxed text-gray-500 mt-1 mb-3">
        {{ post?.postContent }}
      </p>
      <p class="text-md/relaxed text-blue-500 font-bold">See move..</p>
    </div>

    <!-- Post Event Pop -->
    <div class="mt-3 p-3">
      <card-event-pop
        :post="post"
        :authProfileID="authStore.users?.userProfile?.id"
        :on-event-post-pop="onEventPostPop"
      />
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
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";

import imagePostDefault from "@/assets/images/keyboard.jpg";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

import CardDropdownEvent from "@/components/posts/CardDropdownEvent.vue";
import CardEventPop from "@/components/posts/CardEventPop.vue";

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
