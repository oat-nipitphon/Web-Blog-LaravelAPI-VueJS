<template>
  <div class="w-full">
    <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
      <!-- Follower Button -->
      <div class="flex flex-col items-center">
        <div class="grid grid-cols-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            :class="[
              isFollowing
                ? 'text-yellow-400 rounded-full p-1 size-10 m-auto'
                : 'text-gray-700 rounded-full p-1 size-10 m-auto',
            ]"
            @click="props.onFollower(props.profile.profileID, profileIDEvent)"
          >
            <path
              fill-rule="evenodd"
              d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z"
              clip-rule="evenodd"
            />
          </svg>

          <span class="text-sm font-semibold text-gray-700 m-auto">
            {{ countFollowers }}
          </span>
        </div>
      </div>

      <!-- Pop Button -->
      <div class="flex flex-col items-center">
        <div class="grid grid-cols-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            :class="[
              isPoping
                ? 'text-red-500 size-10 roundend-full p-1 m-auto'
                : 'text-gray-700 size-10 roundend-full p-1 m-auto',
            ]"
            @click="props.onPop(props.profile.profileID, profileIDEvent)"
          >
            <path
              d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"
            />
          </svg>
          <span class="text-sm font-semibold text-gray-700 m-auto">
            {{ countPops }}
          </span>
        </div>
      </div>
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
  onPop: {
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

const isPoping = computed(() =>
  props.profile.pops.some(
    (p) =>
      p.profile_id_pop === profileIDEvent.value &&
      (p.status === true || p.status === "true")
  )
);

const countPops = computed(
  () =>
    props.profile.pops.filter((p) => p.status === true || p.status === "true")
      .length
);
</script>
