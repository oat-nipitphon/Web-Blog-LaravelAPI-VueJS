<template>
  <div class="flex items-center space-x-4">
    <!-- Avatar -->
    <img
      class="w-12 h-12 rounded-full border object-cover"
      :src="'data:image/png;base64,' + props.profile?.image?.imageData"
      alt="User Avatar"
    />

    <!-- Profile Info -->
    <div class="flex-1">
      <p class="font-medium text-gray-900">
        {{ props.profile?.firstName }} {{ props.profile?.lastName }}
      </p>
      <p class="text-sm text-gray-500">{{ props.user?.email }}</p>

      <!-- Action Buttons -->
      <div class="mt-2 flex space-x-2">
        <div class="flex items-center space-x-1">
          <!-- Follow/Unfollow Button -->
          <button
            @click="toggleFollowProfile(props.profile?.profileID, authProfileID)"
            :class="[
              isFollowingProfile
                ? 'bg-red-500 text-white hover:bg-red-600'
                : 'bg-blue-500 text-white hover:bg-blue-600',
              'px-3 py-1 text-xs rounded-md transition',
            ]"
          >
            {{ isFollowingProfile ? "Unfollow" : "Follow" }}
          </button>
          <!-- Counter sum -->
          <span class="text-xs text-gray-600">
            {{ followersCount }}
          </span>
        </div>

        <div class="flex items-center space-x-1">
          <!-- Like/Unlike Button -->
          <button
            @click="togglePopProfile(props.profile?.profileID, authProfileID)"
            :class="[
              isLikedProfile
                ? 'bg-pink-500 text-white hover:bg-pink-600'
                : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
              'px-3 py-1 text-xs rounded-md transition flex items-center space-x-1',
            ]"
          >
            <svg
              v-if="isLikedProfile"
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4"
              fill="currentColor"
              viewBox="0 0 16 16"
            >
              <path
                d="M8 15s6-4.686 6-8.333A3.333 3.333 0 0 0 10.667 3C9.4 3 8 4.333 8 4.333S6.6 3 5.333 3A3.333 3.333 0 0 0 2 6.667C2 10.314 8 15 8 15z"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318a4.5 4.5 0 1 1 6.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 0 1 0-6.364z"
              />
            </svg>
            <span class="ml-1">
              {{ likesCount }}
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import Swal from "sweetalert2";
import { useStoreUserProfile } from "@/stores/user-profile";

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();
const props = defineProps({
  profile: Object,
  user: Object,
  authProfileID: Number,
});

// State
const isFollowingProfile = ref(false);
const isLikedProfile = ref(false);

// Counters
const followersCount = computed(() => props.profile?.followers?.length || 0);
const likesCount = computed(() => props.profile?.pops?.length || 0);

// Watch for prop changes
watch(
  () => props.profile,
  (newProfile) => {
    isFollowingProfile.value = !!newProfile?.followers?.find(
      (f) => f.follower_id === props.authProfileID
    );
    isLikedProfile.value = !!newProfile?.pops?.find(
      (p) => p.popProfileID === props.authProfileID
    );
  },
  { immediate: true }
);

// Toggle Follow
const toggleFollowProfile = async (profileID, profileIDfollowers) => {
  const response = await storeProfileFollowers(profileID, profileIDfollowers);
  if (response?.status === 200 || response?.status === 201) {
    isFollowingProfile.value = response.data.following; // true / false
    props.profile.followers = response.data.followers; // update followers array
    Swal.fire("Success", response.data.message, "success");
  }
};

// Toggle Like
const togglePopProfile = async (profileID, profileIDpop) => {
  const response = await storeProfilePop(profileID, profileIDpop);
  if (response?.status === 200 || response?.status === 201) {
    isLikedProfile.value = response.data.liked; // true / false
    props.profile.pops = response.data.pops; // update pops array
    Swal.fire("Success", response.data.message, "success");
  }
};
</script>
