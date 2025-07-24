<template>
  <div
    v-if="user && profile"
    class="grid grid-cols-[auto_80%] md:grid-cols-[auto_80%] sm:grid-cols-1 bg-white w-full shadow-md rounded-lg"
  >
    <!-- รูปโปรไฟล์ -->
    <div class="flex justify-center items-center">
      <img
        :src="
          profile?.image?.imageData
            ? 'data:image/png;base64,' + profile.image.imageData
            : ImageDefault
        "
        alt="Profile"
        class="rounded-full shadow-lg w-16 h-16 object-cover"
      />
    </div>

    <!-- ข้อมูลและไอคอน -->
    <div class="flex flex-col justify-between flex-grow">
      <p class="text-lg font-medium text-gray-950">{{ user?.email }}</p>
      <card-event-follower-pop
        :profile="profile"
        :on-follower="onFollower"
        :on-pop="onPop"
      />
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useStoreUserProfile } from "@/stores/user-profile";
import CardEventFollowerPop from "./CardEventFollowerPop.vue";
import { usePostStore } from "@/stores/post";
import ImageDefault from "@/assets/images/keyboard.jpg";

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
