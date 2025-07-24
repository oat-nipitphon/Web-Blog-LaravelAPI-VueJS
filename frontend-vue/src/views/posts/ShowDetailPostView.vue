<template>
  <div
    class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transition hover:shadow-2xl duration-300"
    v-if="post"
  >
    <!-- Image -->
    <div class="relative">
      <img
        :src="
          post.postImage?.imageData
            ? 'data:image/png;base64,' + post.postImage.imageData
            : imagePostDefault
        "
        alt="Post Image"
        class="w-full h-64 object-cover"
      />
      <div
        class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded"
      >
        {{ formatDateTime.formatDate(post.createdAT) }}
      </div>
    </div>

    <!-- Post Content -->
    <div class="p-5 space-y-4">
      <!-- Title and Type -->
      <div class="flex justify-between items-start">
        <h1 class="text-2xl font-bold text-gray-800">
          {{ post.title }}
        </h1>
        <span
          class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full"
        >
          {{ post.postType?.name }}
        </span>
      </div>

      <!-- Content -->
      <p class="text-gray-600 leading-relaxed">
        {{ post.content }}
      </p>
    </div>

    <!-- Buttons -->
    <div class="grid grid-cols-2 border-t border-gray-200">
      <button
        class="w-full py-3 text-center text-gray-700 hover:bg-gray-100 transition font-semibold"
      >
        ⬅ Back
      </button>
      <button
        class="w-full py-3 text-center text-blue-600 hover:bg-blue-50 transition font-semibold"
      >
        Next ➡
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";
import { usePostStore } from "@/stores/post";
import imagePostDefault from "@/assets/images/keyboard.jpg";

const route = useRoute();
const postStore = usePostStore();
const { post } = storeToRefs(postStore);
const { storeGetPostShowDetail } = usePostStore();

onMounted(async () => {
  await storeGetPostShowDetail(route.params.id);
  console.log("show detail post ", post);
});

const formatDateTime = {
  formatDate(dateTime) {
    if (!dateTime) return "";
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
};
</script>
