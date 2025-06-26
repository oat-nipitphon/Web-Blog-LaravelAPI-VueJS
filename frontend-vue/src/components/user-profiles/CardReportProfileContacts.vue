<template>
  <div
    class="w-full max-w-md m-auto p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700"
  >
    <div class="grid grid-cols-2 items-center mb-4">
      <h5
        class="text-base font-semibold text-gray-900 md:text-xl dark:text-white"
      >
        ช่องทางติดต่อ
      </h5>
      <div class="flex justify-end">
        <CardAddContacts :profileID="profileID" />
      </div>
    </div>

    <ul class="space-y-3" v-if="contacts && contacts.length">
      <li v-for="(contact, index) in contacts" :key="contact.id ?? index">
        <a
          :href="contact.url"
          target="_blank"
          rel="noopener"
          class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-500"
        >
          <img
            v-if="contact.imageData"
            :src="'data:image/png;base64,' + contact.imageData"
            alt="Contact Icon"
            class="w-10 h-10 rounded-sm object-cover"
          />
          <div class="flex flex-col text-left">
            <span class="text-sm font-semibold text-gray-900 dark:text-white">
              {{ contact.name }}
            </span>
            <span class="text-xs text-gray-500 dark:text-gray-300">
              {{ contact.url }}
            </span>
          </div>
        </a>
        <!-- Delete Button -->
        <button
          @click="onDelete(contact.id)"
          class="text-red-500 hover:text-red-600 transition rounded-lg m-auto"
          title="Delete"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
          {{ contact.id }}
        </button>
      </li>
    </ul>

    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
      ไม่มีข้อมูลช่องทางติดต่อ
    </p>
  </div>
</template>
<script setup>
import { useRoute } from "vue-router";
import CardAddContacts from "@/components/user-profiles/CardAddContacts.vue";

const props = defineProps({
  profileID: {
    type: Number,
    required: true,
  },
  contacts: {
    type: Object,
    required: true,
  },
});

const onDelete = async ($id) => {
  console.log("on delete ", $id);
  const response = await fetch(`/api/user_profile_contacts/${id}`, {
    method: "DELETE",
    headers: {
      authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  });

  if (response.status !== 200) {
    console.error("delete contacts false", response.error);
  }

  console.log("delete contacts success", response);

};
</script>
