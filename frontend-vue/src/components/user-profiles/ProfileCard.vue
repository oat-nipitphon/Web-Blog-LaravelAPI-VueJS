<template>
  <div class="w-max-full bg-white border rounded-lg shadow-md m-2 p-2">
    <div class="grid grid-cols-[auto_40%]">
      <div class="flex items-start justify-start ml-5 mt-3">
        <!-- Profile Image -->
        <div v-if="props.profile?.image?.imageData">
          <img
            :src="'data:image/png;base64,' + props.profile?.image?.imageData"
            class="size-10 rounded-full"
            alt="ProfileImage"
          />
        </div>
        <div v-else>
          <img
            class="size-10 rounded-full"
            src="../../assets/images/keyboard.jpg"
            alt="ProfileImage"
          />
        </div>

        <!-- Profile Info -->
        <div class="grid grid-rows-3">
          <label class="ml-2">{{ onFullName }}</label>
          <label class="ml-2">{{ props.user.username }}</label>
          <label class="ml-2">{{ props.user.email }}</label>
        </div>
      </div>

      <!-- Followers & Pops -->
      <div class="flex justify-end items-end">
        <div class="text-sm text-gray-500 dark:text-gray-400">
          <div class="grid grid-rows-2">
            <div class="flex justify-center">
              <button
                class="btn btn-sm"
                @click="
                  onFollowersProfile(
                    props.profile.profileID,
                    authStore.users.userProfile.id
                  )
                "
              >
                <div class="grid grid-cols-3">
                  <!-- Icon -->
                  <div class="flex justify-center">
                    <svg
                      v-if="
                        props?.profile?.followers.some(
                          (row) =>
                            row.profile_id_followers ===
                              authStore.users.userProfile.id &&
                            (row.status === 'true' || row.status === true)
                        )
                      "
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      class="bi bi-bell-fill text-yellow-300 w-[30px] h-[30px]"
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
                      class="bi bi-bell-fill w-[30px] h-[30px]"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
                      />
                    </svg>
                  </div>
                  <div class="flex justify-center">
                    <p class="m-auto mt-auto text-gray-700 text-md-2xl">
                      {{
                        props?.profile?.followers.filter(
                          (row) => row.status === "true" || row.status === true
                        ).length
                      }}
                    </p>
                  </div>
                </div>
              </button>
            </div>
            <div class="flex justify-center">
              <button
                class="btn btn-sm"
                @click="
                  onPopsProfile(
                    props.profile.profileID,
                    authStore.users.userProfile.id
                  )
                "
              >
                <div class="grid grid-cols-3">
                  <div class="flex justify-center">
                    <svg
                      v-if="
                        props?.profile?.pops.some(
                          (row) =>
                            row.profile_id_pop ===
                              authStore.users.userProfile.id &&
                            (row.status === 'true' || row.status === true)
                        )
                      "
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      class="bi bi-heart-fill text-red-600 w-[30px] h-[30px]"
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
                      class="bi bi-heart-fill w-[30px] h-[30px]"
                      viewBox="0 0 16 16"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
                      />
                    </svg>
                  </div>
                  <div class="flex justify-center">
                    <p class="m-auto mt-auto text-gray-700 text-md-2xl">
                      {{
                        props?.profile?.pops.filter(
                          (row) => row.status === "true" || row.status === true
                        ).length
                      }}
                    </p>
                  </div>
                </div>
              </button>
            </div>
            <!-- <event-follower-card
              :profile="props?.profile"
              :profile-id-follower="authStore.users?.userProfile?.id"
              :on-followers-profile="onFollowersProfile"
            /> -->
            <!-- <event-pop-card
              :profile="props?.profile"
              :profile-id-follower="authStore.users?.userProfile?.id"
              :on-pops-profile="onPopsProfile"
            /> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
  profile: {
    type: Object,
    default: () => ({}),
  },
});

import { computed } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { useStoreUserProfile } from "@/stores/user-profile";
import EventPopCard from "@/components/user-profiles/EventPopCard.vue";
import EventFollowerCard from "@/components/user-profiles/EventFollowerCard.vue";

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();

const onFollowersProfile = async (profileID, profileIDFollowers) => {
  console.log("on followers profile", profileID, profileIDFollowers);
  const success = await storeProfileFollowers(profileID, profileIDFollowers);
  if (success) return;
  return;
};

const onPopsProfile = async (profileID, profileIDPops) => {
  console.log("on pops profile", profileID, profileIDPops);
  const success = await storeProfilePop(profileID, profileIDPops);
  if (success) return;
  return;
};

const onFullName = computed(
  () =>
    props.profile.titleName +
    " " +
    props.profile.firstName +
    " " +
    props.profile.lastName
);

const onCheckProfileStatus = computed(() => ({
  isFollower:
    (props.profile?.followers?.length > 0 &&
      props.profile.followers.some(
        (f) =>
          f?.profile_id_followers === authStore.users.userProfile.id &&
          (f.status === true || f.status === "true")
      )) ||
    false,

  isFollowersCount:
    props.profile?.followers?.length > 0
      ? props.profile.followers.filter(
          (row) => row.status === "true" || row.status === true
        ).length
      : 0,

  isPop:
    (props.profile?.pops?.length > 0 &&
      props.profile.pops.some(
        (p) =>
          p?.profile_id_pop === authStore.users.userProfile.id &&
          (p.status === true || p.status === "true")
      )) ||
    false,

  isPopsCount:
    props.profile?.pops?.length > 0
      ? props.profile.pops.filter(
          (row) => row.status === "true" || row.status === true
        ).length
      : 0,
}));

</script>
