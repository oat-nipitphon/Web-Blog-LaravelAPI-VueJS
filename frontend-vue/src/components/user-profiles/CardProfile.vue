<template>
  <div class="w-full grid grid-cols-[auto_70%]">
    <div class="flex justify-center items-center">
      <img
        :src="
          profile?.image?.imageData
            ? 'data:image/png;base64,' + profile.image.imageData
            : ImageDefault
        "
        alt="Profile"
        :class="[
          user?.statusLogin?.status === 'online'
          ? 'border-blue-600 m-auto size-17 rounded-md border-3'
          : 'border-red-500 m-auto size-17 rounded-md border-3'
        ]"
      />
    </div>
   <div class="grid grid-rows-2">
    <div class="p-2 font-medium text-md text-gray-600 ml-3">
      {{ user?.email }}
    </div>
     <div class="grid grid-cols-2 ml-3">
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
