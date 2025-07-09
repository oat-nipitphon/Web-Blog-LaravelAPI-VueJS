<template>
  <div
    class="bg-white border rounded-lg shadow-md p-4 dark:bg-gray-800 dark:border-gray-700"
  >
    <!-- Profile Header -->
    <div class="flex items-center space-x-4">
      <!-- Avatar -->
      <img
        :src="profile?.image?.imageData
          ? 'data:image/png;base64,' + profile?.image?.imageData
          : defaultImage"
        class="w-12 h-12 rounded-full border object-cover"
        alt="ProfileImage"
      />
      <!-- Name -->
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
          {{ profile?.titleName }} {{ profile?.firstName }} {{ profile?.lastName }}
        </h3>
      </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-2 gap-4 mt-4">
      <!-- Followers Button -->
      <button
        @click="onFollowers(profile?.profileID, authProfileID)"
        class="flex items-center justify-center p-2 rounded-lg border hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
      >
        <svg
          v-if="isFollowing"
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="w-6 h-6 text-yellow-400 animate-pulse"
          viewBox="0 0 16 16"
        >
          <path
            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
          />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="w-6 h-6 text-gray-400"
          viewBox="0 0 16 16"
        >
          <path
            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
          />
        </svg>
        <span class="ml-2 text-gray-700 dark:text-gray-300">
          {{ followerCount }} ติดตาม
        </span>
      </button>

      <!-- Pop Button -->
      <button
        @click="onPop(profile?.profileID, authProfileID)"
        class="flex items-center justify-center p-2 rounded-lg border hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
      >
        <svg
          v-if="isPopped"
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="w-6 h-6 text-red-500 animate-bounce"
          viewBox="0 0 16 16"
        >
          <path
            fill-rule="evenodd"
            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
          />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="w-6 h-6 text-gray-400"
          viewBox="0 0 16 16"
        >
          <path
            fill-rule="evenodd"
            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
          />
        </svg>
        <span class="ml-2 text-gray-700 dark:text-gray-300">
          {{ popCount }} ถูกใจ
        </span>
      </button>
    </div>
  </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { useStoreUserProfile } from "@/stores/user-profile";

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();
const props = defineProps({
  profile: Object,
  authProfileID: Number,
});

// Default avatar
const defaultImage = new URL('@/assets/images/keyboard.jpg', import.meta.url).href;

// Computed reactive states
const followerCount = computed(
  () => props.profile?.followers.filter((f) => f.status === "true").length || 0
);
const popCount = computed(
  () => props.profile?.pops.filter((p) => p.status === "true").length || 0
);

const isFollowing = computed(() =>
  props.profile?.followers.some(
    (f) =>
      f.profile_id_followers === props.authProfileID && f.status === "true"
  )
);

const isPopped = computed(() =>
  props.profile?.pops.some(
    (p) => p.profile_id_pop === props.authProfileID && p.status === "true"
  )
);

// Actions
const onFollowers = async (profileID, authProfileID) => {
  const success = await storeProfileFollowers(profileID, authProfileID);
  if (success) {
    // Toggle local state without reloading
    const existing = props.profile.followers.find(
      (f) => f.profile_id_followers === authProfileID
    );
    if (existing) {
      existing.status = existing.status === "true" ? "false" : "true";
    } else {
      props.profile.followers.push({
        profile_id_followers: authProfileID,
        status: "true",
      });
    }
  }
};

const onPop = async (profileID, authProfileID) => {
  const success = await storeProfilePop(profileID, authProfileID);
  if (success) {
    // Toggle local state without reloading
    const existing = props.profile.pops.find(
      (p) => p.profile_id_pop === authProfileID
    );
    if (existing) {
      existing.status = existing.status === "true" ? "false" : "true";
    } else {
      props.profile.pops.push({
        profile_id_pop: authProfileID,
        status: "true",
      });
    }
  }
};
</script>
