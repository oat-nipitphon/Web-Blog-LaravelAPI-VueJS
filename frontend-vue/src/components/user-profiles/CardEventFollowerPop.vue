<template>
  <div class="w-full">
    <div class="grid grid-cols-2 gap-4">
      <!-- Follower Button -->
      <button
        class="flex items-center justify-center"
        @click="props.onFollower(props.profile.profileID, profileIDEvent)"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          :class="{
            'size-6': true,
            'bg-yellow-500': isFollowing,
            'bg-gray-800': !isFollowing,
          }"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
          />
        </svg>
      </button>
      <span class="text-sm font-semibold"
        >จำนวนผู้ติดตาม {{ countFollowers }}</span
      >

      <!-- Pop Button -->
      <button
        class="flex items-center justify-center"
        @click="props.onPop(props.profile.profileID, profileIDEvent)"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          viewBox="0 0 16 16"
          :class="[
            isPoping === true ? 'text-red-500' : 'text-gray-800'
          ]"
        >
          <path
            fill-rule="evenodd"
            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
          />
        </svg>
      </button>
      <span class="text-sm font-semibold">จำนวนผู้ถูกใจ {{ countPops }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, watch, onMounted } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const profileIDEvent = ref(null);
profileIDEvent.value = authStore.users.userProfile.id ?? null;
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
      f.profile_id_followers === profileIDEvent &&
      (f.status === "true" || f.status === true)
  )
);
const countFollowers = computed(
  () =>
    props.profile?.followers?.filter(
      (f) => f.status === true || f.status === "true"
    ).length
);

const isPoping = computed(() =>
  props.profile?.pops.some(
    (p) =>
      p.profile_id_pop === profileIDEvent &&
      (p.status === "true" || p.status === true)
  )
);
console.log('is pop', isPoping)
const countPops = computed(
  () =>
    props.profile?.pops?.filter((p) => p.status === true || p.status === "true")
      .length
);
</script>
