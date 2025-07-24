<template>
  <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-md">
    <!-- Profile Image -->
    <img
      :src="
        profile?.image?.imageData
          ? 'data:image/png;base64,' + profile.image.imageData
          : ImageDefault
      "
      alt="Profile"
      class="w-16 h-16 rounded-full border-2 border-gray-300 shadow-lg transition-transform duration-300 hover:scale-105 object-cover"
    />

    <!-- User Info & Actions -->
    <div class="flex flex-col flex-1">
      <!-- Email -->
      <p class="text-lg font-semibold text-gray-700 mb-2">
        {{ user?.email }}
      </p>

      <!-- Actions -->
      <div class="flex gap-2">
        <card-event-follower :profile="profile" :on-follower="onFollower" />
        <card-event-pop :profile="profile" :on-pop="onPop" />
      </div>
    </div>
  </div>
</template>


<script setup>
import { computed, onMounted } from "vue";
import { useStoreUserProfile } from "@/stores/user-profile";
import CardEventFollowerPop from "./CardEventFollowerPop.vue";
import { usePostStore } from "@/stores/post";
import ImageDefault from "@/assets/images/keyboard.jpg";
import CardEventFollower from "@/components/user-profiles/CardEventFollower.vue";
import CardEventPop from "@/components/user-profiles/CardEventPop.vue";
const { storeGetPosts } = usePostStore();
const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();

defineProps({
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
      nickName: "",
      image: null,
    }),
  },
});

const onFollower = async (profileID, profileIDEvent) => {
  storeProfileFollowers(profileID, profileIDEvent);
  await storeGetPosts();
};

const onPop = async (profileID, profileIDEvent) => {
  await storeProfilePop(profileID, profileIDEvent);
  await storeGetPosts();
};

onMounted(async () => {
  await storeGetPosts();
});
</script>
