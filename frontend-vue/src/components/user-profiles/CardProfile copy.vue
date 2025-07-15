<template>
  <div class="w-max-full bg-white border rounded-lg shadow-md m-2 p-2">
    <div class="grid grid-cols-[auto_40%]">
      <div class="flex items-start justify-start ml-5 mt-3">
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
        <div class="grid grid-rows-3">
          <label class="ml-2">{{ onFullName }}</label>
          <label class="ml-2">{{ props.user.username }}</label>
          <label class="ml-2">{{ props.user.email }}</label>
        </div>
      </div>

      <!-- space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3 -->
      <div class="flex justify-end items-end">
        <div class="text-sm text-gray-500 dark:text-gray-400">
          <div class="grid grid-rows-2">
            <!-- Followers Profile create post  -->
            <div class="flex justify-center">
              <div class="grid grid-cols-2">
                <div class="flex justify-center items-center">
                  <button
                    class="btn btn-sm"
                    @click="
                      onFollowersProfile(
                        props?.profile?.profileID,
                        authProfileID
                      )
                    "
                  >
                    <div class="grid grid-cols-3">
                      <div class="flex justify-center">
                        <svg
                          v-if="onCheckProfileStatus.isFollowers"
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
                          <span>👥 {{ onCheckProfileStatus.isFollowersCount }} Followers</span>
                        </p>
                      </div>
                      <div class="flex justify-center m-auto">ติดตาม</div>
                    </div>
                  </button>
                </div>
              </div>
            </div>

            <!-- Popularity Profile create post Dis Like -->
            <div class="flex justify-center">
              <div class="grid grid-cols-2">
                <div class="flex justify-center items-center">
                  <button
                    class="btn btn-sm"
                    @click="
                      onPopProfile(props?.profile?.profileID, authProfileID)
                    "
                  >
                    <div class="grid grid-cols-3">
                      <div class="flex justify-center">
                        <svg
                          v-if="onCheckProfileStatus.isPop"
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
                          <span>⭐ {{ onCheckProfileStatus.isPopsCount }} Pops</span>
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
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
const authProfileID = ref(null);
authProfileID.value = authStore?.users?.userProfile?.id;
console.log("card profile id ", authProfileID.value);
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
    default: () => ({}),
  },
  onFollowersProfile: {
    type: Function,
    default: true,
  },
  onPopProfile: {
    type: Function,
    default: true,
  },
  onCheckProfileStatus: {
    type: Object,
    default: () => ({
        isFollowers: false,
        isFollowersCount: 0,
        isPop: false,
        isPopsCount: 0,
    }),
  },

});

const onFullName = computed(
  () =>
    props.profile.titleName +
    " " +
    props.profile.firstName +
    " " +
    props.profile.lastName
);


</script>
