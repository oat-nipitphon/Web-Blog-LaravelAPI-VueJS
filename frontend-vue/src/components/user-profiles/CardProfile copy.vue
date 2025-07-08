<template>
  <div class="flex items-center space-x-4">
    <!-- Avatar -->
    <img
      class="w-12 h-12 rounded-full border object-cover"
      :src="'data:image/png;base64,' + profile?.image?.imageData"
      alt="User Avatar"
    />

    <!-- Profile Info -->
    <div class="flex-1">
      <p class="font-medium text-gray-900">
        {{ profile?.firstName }} {{ profile?.lastName }}
      </p>
      <p class="text-sm text-gray-500">{{ user?.email }}</p>

      <!-- Action Buttons -->
      <div class="mt-2 flex space-x-2">
        <div class="flex items-center space-x-1">
          <!-- Follow/Unfollow Button -->
          <button
            @click="toggleFollowProfile(profile?.profileID, authProfileID)"
            :class="[
              isFollowingProfile
                ? 'bg-red-500 text-white hover:bg-red-600'
                : 'bg-blue-500 text-white hover:bg-blue-600',
              'px-3 py-1 text-xs rounded-md transition',
            ]"
            :disabled="loadingFollow"
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
            @click="togglePopProfile(profile?.profileID, authProfileID)"
            :class="[
              isLikedProfile
                ? 'bg-pink-500 text-white hover:bg-pink-600'
                : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
              'px-3 py-1 text-xs rounded-md transition flex items-center space-x-1',
            ]"
            :disabled="loadingLike"
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
import { computed, toRefs, ref } from "vue";
import Swal from "sweetalert2";
import { useStoreUserProfile } from "@/stores/user-profile";

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();
const props = defineProps({
  profile: Object,
  user: Object,
  authProfileID: Number,
});

// Destructure props safely
const { profile, user, authProfileID } = toRefs(props);

// Loading states
const loadingFollow = ref(false);
const loadingLike = ref(false);

// Computed: Counters
const followersCount = computed(() => profile.value?.followers?.length || 0);
const likesCount = computed(() => profile.value?.pops?.length || 0);

// Computed: Following & Liked status
const isFollowingProfile = computed(() =>
  profile.value?.followers?.some(
    (f) => f.follower_id === authProfileID.value
  )
);
const isLikedProfile = computed(() =>
  profile.value?.pops?.some((p) => p.popProfileID === authProfileID.value)
);

// Toggle Follow
const toggleFollowProfile = async (profileID, profileIDfollowers) => {
  loadingFollow.value = true;
  try {
    const response = await storeProfileFollowers(profileID, profileIDfollowers);
    if (response?.status === 200 || response?.status === 201) {
      profile.value.followers = response.data.followers; // update
      Swal.fire("Success", response.data.message, "success");
    }
  } catch (err) {
    Swal.fire("Error", err.message || "Failed to update follow status", "error");
  } finally {
    loadingFollow.value = false;
  }
};

// Toggle Like
const togglePopProfile = async (profileID, profileIDpop) => {
  loadingLike.value = true;
  try {
    const response = await storeProfilePop(profileID, profileIDpop);
    if (response?.status === 200 || response?.status === 201) {
      profile.value.pops = response.data.pops; // update
      Swal.fire("Success", response.data.message, "success");
    }
  } catch (err) {
    Swal.fire("Error", err.message || "Failed to update like status", "error");
  } finally {
    loadingLike.value = false;
  }
};
</script>
