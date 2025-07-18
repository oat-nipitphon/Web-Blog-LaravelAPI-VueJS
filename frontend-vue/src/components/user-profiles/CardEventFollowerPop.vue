<template>
  <div class="w-full">
    <div class="grid grid-cols-2 gap-4">
      <!-- Follower Button -->
      <button
        class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-yellow-400 text-white rounded-full shadow-md hover:bg-yellow-500 active:scale-95 transition duration-200"
        @click="onFollower(props.profile.profileID, profileIDEvent)"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="w-5 h-5 text-white"
          viewBox="0 0 16 16"
        >
          <path
            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
          />
        </svg>
        <span class="text-sm font-semibold">ติดตาม</span>
      </button>

      <!-- Pop Button -->
      <button
        class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-pink-500 text-white rounded-full shadow-md hover:bg-pink-600 active:scale-95 transition duration-200"
        @click="onPop(props.profile.profileID, profileIDEvent)"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="w-5 h-5 text-white"
          viewBox="0 0 16 16"
        >
          <path
            fill-rule="evenodd"
            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
          />
        </svg>
        <span class="text-sm font-semibold">ถูกใจ</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const profileIDEvent = ref(null);
profileIDEvent.value = authStore.users.userProfile.id;
console.log("card event follower pop", profileIDEvent);
const props = defineProps({
  profile: {
    type: Object,
    default: () => ({
      profileID: null,
      followers: [],
      pops: [],
    }),
  },
  onFollower: {
    type: Function,
    required: null,
  },
  onPop: {
    type: Function,
    required: null,
  },
});
</script>
