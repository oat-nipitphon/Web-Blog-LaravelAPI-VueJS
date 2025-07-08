<template>
  <div
    class="bg-white border-gray-200 md:rounded-es-lg dark:bg-gray-800 dark:border-gray-700"
  >
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
      <div>
        <label class="ml-2">{{ props.profile?.titleName }}</label>
        <label class="ml-2">{{ props.profile?.firstName }}</label>
        <label class="ml-2">{{ props.profile?.lastName }}</label>
      </div>
    </div>

    <div
      class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3"
    >
      <div class="text-sm text-gray-500 dark:text-gray-400">
        <div class="grid grid-cols-2">
          <!-- Followers Profile create post  -->
          <div class="flex justify-center">
            <div class="grid grid-cols-2">
              <div class="flex justify-center items-center">
                <button
                  class="btn btn-sm"
                  @click="
                    onFollowers(props?.profile?.profileID, props?.authProfileID)
                  "
                >
                  <div class="grid grid-cols-3">
                    <div class="flex justify-center">
                      <svg
                        v-if="
                          props?.profile?.followers.some(
                            (followers) =>
                              followers?.profile_id_followers ===
                                props?.authProfileID &&
                              followers.status === 'true'
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
                            (followers) => followers?.status === "true"
                          ).length
                        }}
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
                    onPop(props?.profile?.profileID, props?.authProfileID)
                  "
                >
                  <div class="grid grid-cols-3">
                    <div class="flex justify-center">
                      <svg
                        v-if="
                          props?.profile?.pops.some(
                            (pop) =>
                              pop.profile_id_pop === props?.authProfileID &&
                              pop.status === 'true'
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
                            (popLike) => popLike?.status === "true"
                          ).length || 0
                        }}
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
</template>

<script setup>
import { useStoreUserProfile } from "@/stores/user-profile";

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();
const props = defineProps({
  profile: Object,
  user: Object,
  authProfileID: Number,
});

const onFollowers = async (profileID, authProfileID) => {
  const success = await storeProfileFollowers(profileID, authProfileID);
  if (success) {
    return;
  }
  return;
};

const onPop = async (profileID, authProfileID) => {
  const success = await storeProfilePop(profileID, authProfileID);
  if (success) {
    return;
  }
  return;
};
</script>
