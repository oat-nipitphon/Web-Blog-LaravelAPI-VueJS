<template>
  <div v-if="user && profile" class="">
    <div class="flex">
      <img
        :src="
          profile?.image?.imageData
            ? 'data:image/png;base64,' + profile.image.imageData
            : ImageDefault
        "
        alt="Profile"
        class="rounded-full shadow-lg size-15 object-cover"
      />
      <p class="text-md font-medium text-gray-500 ml-5">
        {{ user?.email }}
      </p>
             <!-- <card-event-follower :profile="profile" :on-follower="onFollower" /> -->
        <!-- <card-event-pop :profile="profile" :on-pop="onPop" /> -->
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
