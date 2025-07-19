<template>
  <div
    class="bg-white rounded-2xl shadow-xl p-6 transition hover:scale-[1.02] duration-300"
  >
    <ul role="list" class="grid gap-6">
      <li v-if="props.user && props.profile">
        <div class="flex items-center gap-4">
          <!-- Profile Image -->
          <img
            v-if="props.profile?.image?.imageData"
            :src="'data:image/png;base64,' + props.profile?.image?.imageData"
            alt="Profile"
            class="w-24 h-24 rounded-full border-4 border-indigo-500 shadow-lg transition-transform duration-500 hover:rotate-6 hover:scale-105"
          />
          <img
            v-else
            src="@/assets/images/keyboard.jpg"
            alt="Default"
            class="w-24 h-24 rounded-full border-4 border-gray-400 shadow-lg transition-transform duration-500 hover:rotate-6 hover:scale-105"
          />

          <!-- User Info -->
          <div class="flex flex-col space-y-2">
            <p class="text-xl font-bold text-indigo-800 tracking-wide">
              {{ props.user?.email }}
            </p>
            <card-event-follower-pop
              :profile="props.profile"
              :on-follower="onFollower"
              :on-pop="onPop"
            />
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStoreUserProfile } from "@/stores/user-profile";
import CardEventFollowerPop from "./CardEventFollowerPop.vue";

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

console.log("card profile ", props.profile);

const onFollower = async (profileID, profileIDEvent) => {
  console.log("on follower ", profileID, profileIDEvent);
};
const onPop = async (profileID, profileIDEvent) => {
  console.log("on pop ", profileID, profileIDEvent);
};
</script>
