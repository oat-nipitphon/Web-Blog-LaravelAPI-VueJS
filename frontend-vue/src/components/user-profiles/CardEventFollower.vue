<template>
  <div class="m-auto">
      <div class="grid grid-cols-2">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          :class="[
            isFollowing
              ? 'text-yellow-400 rounded-full p-1 size-10'
              : 'text-gray-700 rounded-full p-1 size-10',
          ]"
          @click="props.onFollower(props.profile.profileID, profileIDEvent)"
        >
          <path
            fill-rule="evenodd"
            d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z"
            clip-rule="evenodd"
          />
        </svg>

        <span class="text-md font-semibold text-gray-700 m-auto">
          {{ countFollowers }}
        </span>
      </div>
  </div>
</template>
<script setup>
import { ref, computed } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const profileIDEvent = ref(authStore.users.userProfile.id ?? null);

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
    required: true,
  },
});

const isFollowing = computed(() =>
  props.profile.followers.some(
    (f) =>
      f.profile_id_followers === profileIDEvent.value &&
      (f.status === true || f.status === "true")
  )
);

const countFollowers = computed(
  () =>
    props.profile.followers.filter(
      (f) => f.status === true || f.status === "true"
    ).length
);
</script>
