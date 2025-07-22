<template>
  <article class="w-full max-w-3xl mx-auto p-3">
    <div class="bg-white border rounded-lg shadow-md p-4 group relative">
      <div v-if="props.user && props.profile">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
          <!-- Profile Image -->
          <img
            v-if="props.profile?.image?.imageData"
            :src="'data:image/png;base64,' + props.profile?.image?.imageData"
            alt="Profile"
            class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full border-4 border-indigo-500 shadow-lg transition-transform duration-500 hover:rotate-6 hover:scale-105"
          />
          <img
            v-else
            src="@/assets/images/keyboard.jpg"
            alt="Default"
            class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full border-4 border-gray-400 shadow-lg transition-transform duration-500 hover:rotate-6 hover:scale-105"
          />

          <!-- User Info -->
          <div class="flex flex-col space-y-2">
            <p
              class="text-lg sm:text-xl md:text-2xl font-bold text-indigo-800 tracking-wide"
            >
              {{ props.user?.email }}
            </p>
            <card-event-follower-pop
              :profile="props.profile"
              :on-follower="onFollower"
              :on-pop="onPop"
            />
          </div>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useStoreUserProfile } from "@/stores/user-profile";
import CardEventFollowerPop from "./CardEventFollowerPop.vue";
import { usePostStore } from "@/stores/post";
const { storeGetPosts } = usePostStore();
const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();

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
