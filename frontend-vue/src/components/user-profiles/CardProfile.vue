<template>
  <div class="w-full max-w-3xl bg-white border rounded-xl shadow-lg m-4 p-4">
    <div class="grid grid-cols-[auto_35%] gap-4">
      <!-- Left: Profile Info -->
      <div class="flex items-center space-x-4 ml-5 mt-3">
        <!-- Profile Image -->
        <div>
          <img
            :src="
              props.profile?.image?.imageData
                ? 'data:image/png;base64,' + props.profile?.image?.imageData
                : require('../../assets/images/keyboard.jpg')
            "
            class="w-20 h-20 rounded-full border shadow-md object-cover"
            alt="ProfileImage"
          />
        </div>

        <!-- User Details -->
        <div class="flex flex-col justify-center space-y-1">
          <p class="text-lg font-bold text-gray-900">
            {{ onFullName }}
          </p>
          <p class="text-base text-gray-700">
            {{ props.user.username }}
          </p>
          <p class="text-base text-gray-500">
            {{ props.user.email }}
          </p>
        </div>
      </div>

      <!-- Right: Followers & Popularity -->
      <div class="flex flex-col justify-end items-end space-y-4">
        <!-- Followers -->
        <button
          class="flex items-center space-x-2 px-3 py-2 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition"
          @click="
            onFollowersProfile(
              props?.profile?.profileID,
              authStore?.users?.userProfile?.id
            )
          "
        >
          <svg
            v-if="
              props?.profile?.followers.some(
                (f) =>
                  f?.profile_id_followers ===
                    authStore?.users?.userProfile?.id && f.status === 'true'
              )
            "
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-bell-fill text-yellow-400 w-7 h-7"
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
            class="bi bi-bell w-7 h-7"
            viewBox="0 0 16 16"
          >
            <path
              d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
            />
          </svg>
          <span class="text-lg font-medium text-gray-800">
            {{ onSumFollowersProfile }}
          </span>
        </button>

        <!-- Popularity -->
        <button
          class="flex items-center space-x-2 px-3 py-2 bg-red-100 rounded-lg hover:bg-red-200 transition"
          @click="
            onPopProfile(
              props?.profile?.profileID,
              authStore?.users?.userProfile?.id
            )
          "
        >
          <svg
            v-if="
              props?.profile?.pops.some(
                (p) =>
                  p.profile_id_pop === authStore?.users?.userProfile?.id &&
                  p.status === 'true'
              )
            "
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-heart-fill text-red-500 w-7 h-7"
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
            class="bi bi-heart w-7 h-7"
            viewBox="0 0 16 16"
          >
            <path
              fill-rule="evenodd"
              d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
            />
          </svg>
          <span class="text-lg font-medium text-gray-800">
            {{ onSumCountPopProfile }}
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const props = defineProps({
  user: {
    type: Object,
    default: () => ({
      email: "",
      username: "",
    }),
  },
  profile: {
    type: Object,
    default: () => ({
      titleName: "",
      firstName: "",
      lastName: "",
      image: null,
      pops: [],
      followers: [],
    }),
  },
  onFollowersProfile: {
    type: Function,
    default: true,
  },
  onPopProfile: {
    type: Function,
    default: true,
  },
});

const onFullName = computed(
  () =>
    props.profile.titleName +
    " " +
    props.profile.firstName +
    " " +
    props.profile.lastName
);

const onSumCountPopProfile = computed(
  () => props.profile.pops.filter((row) => row.status === true).length || 0
);

const onSumFollowersProfile = computed(
  () => props.profile.followers.filter((row) => row.status === true).length || 0
);
</script>
