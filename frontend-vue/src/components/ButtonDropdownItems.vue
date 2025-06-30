<template>
  <div class="relative inline-block text-left" @click.outside="isOpen = false">
    <button
      @click="toggleDropdown"
      class="inline-flex justify-center w-full rounded-md bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 transition"
    >
      Options
      <svg
        class="-mr-1 ml-2 h-5 w-5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        />
      </svg>
    </button>

    <!-- Dropdown Menu -->
    <div
      v-if="isOpen"
      class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50"
    >
      <div class="py-1">
        <a
          href="#"
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
          @click.prevent="selectOption('Edit')"
        >
          Edit
        </a>
        <a
          href="#"
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
          @click.prevent="selectOption('Duplicate')"
        >
          Duplicate
        </a>
        <a
          href="#"
          class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100"
          @click.prevent="selectOption('Delete')"
        >
          Delete
        </a>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";

const isOpen = ref(false);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const selectOption = (option) => {
  console.log("Selected:", option);
  isOpen.value = false;
};
</script>
