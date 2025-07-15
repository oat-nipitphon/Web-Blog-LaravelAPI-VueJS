<template>
  <div class="w-max-full bg-white border rounded-lg shadow-md m-2 p-2">
    <div class="grid grid-cols-[auto_40%]">
      <div class="flex items-start justify-start ml-5 mt-3">
        <!-- Profile Image -->
        <div v-if="localProfile?.image?.imageData">
          <img
            :src="'data:image/png;base64,' + localProfile.image.imageData"
            class="size-6/12 rounded-sm"
            alt="ProfileImage"
          />
        </div>
        <div v-else>
          <img
            class="size-6/12 rounded-sm"
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
          <div class="grid grid-rows-2 gap-2">
            <!-- Follower Button -->
            <div class="flex justify-center">
              <button
                class="btn btn-sm"
                @click="
                  onFollowersProfile(
                    localProfile.profileID,
                    authStore.users.userProfile.id
                  )
                "
              >
                <div class="grid grid-cols-3 gap-1">
                  <div class="flex justify-center">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      :class="[
                        'bi bi-bell-fill w-[30px] h-[30px]',
                        onCheckProfileStatus.isFollower
                          ? 'text-yellow-300'
                          : '',
                      ]"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
                      />
                    </svg>
                  </div>
                  <div class="flex justify-center">
                    <p class="m-auto text-gray-700 text-md-2xl">
                      {{ onCheckProfileStatus.isFollowersCount }}
                    </p>
                  </div>
                  <div class="flex justify-center m-auto">ติดตาม</div>
                </div>
              </button>
            </div>

            <!-- Pop Button -->
            <div class="flex justify-center">
              <button
                class="btn btn-sm"
                @click="
                  onPopsProfile(
                    localProfile.profileID,
                    authStore.users.userProfile.id
                  )
                "
              >
                <div class="grid grid-cols-3 gap-1">
                  <div class="flex justify-center">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      :class="[
                        'bi bi-heart-fill w-[30px] h-[30px]',
                        onCheckProfileStatus.isPop ? 'text-red-600' : '',
                      ]"
                      viewBox="0 0 16 16"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
                      />
                    </svg>
                  </div>
                  <div class="flex justify-center">
                    <p class="m-auto text-gray-700 text-md-2xl">
                      {{ onCheckProfileStatus.isPopsCount }}
                    </p>
                  </div>
                  <div class="flex justify-center m-auto">ถูกใจ</div>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed, watch } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import { useStoreUserProfile } from "@/stores/user-profile";

const { storeGetPosts } = usePostStore();
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

const authStore = useAuthStore();
const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();

const localProfile = reactive({ ...props.profile });

const onFullName = computed(
  () =>
    `${localProfile.titleName} ${localProfile.firstName} ${localProfile.lastName}`
);

const onFollowersProfile = async (profileID, profileIDFollowers) => {
  const success = await storeProfileFollowers(profileID, profileIDFollowers);
  if (success?.followers) {
    localProfile.followers = success.followers;
  }
  await storeGetPosts();
};

const onPopsProfile = async (profileID, profileIDPops) => {
  const success = await storeProfilePop(profileID, profileIDPops);
  if (success?.pops) {
    localProfile.pops = success.pops;
  }
  await storeGetPosts();
};

const onCheckProfileStatus = computed(() => ({
  isFollower:
    localProfile?.followers?.some(
      (f) =>
        f?.profile_id_followers === authStore.users.userProfile.id &&
        (f.status === true || f.status === "true")
    ) || false,

  isFollowersCount:
    localProfile?.followers?.filter(
      (row) => row.status === "true" || row.status === true
    ).length || 0,

  isPop:
    localProfile?.pops?.some(
      (p) =>
        p?.profile_id_pop === authStore.users.userProfile.id &&
        (p.status === true || p.status === "true")
    ) || false,

  isPopsCount:
    localProfile?.pops?.filter(
      (row) => row.status === "true" || row.status === true
    ).length || 0,
}));

watch(
  () => props.profile,
  (newVal) => Object.assign(localProfile, newVal)
);
</script>
