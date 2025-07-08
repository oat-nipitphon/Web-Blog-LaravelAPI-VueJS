<template>
  <article
    class="w-full rounded-lg shadow-lg transition hover:shadow-lg m-auto"
  >
    <div class="grid grid-cols-2 mb-2 mt-2 p-2 bg-white">
      <div>
        <CardProfile
          :profile="post.userProfile"
          :user="post.user"
          :authProfileID="authStore.users?.userProfile?.id"
        />
      </div>

      <div class="flex justify-end items-end mr-5 mt-auto mb-auto">
        <Menu as="div" class="relative inline-block text-left">
          <div>
            <MenuButton class="inline-flex text-gray-700 hover:text-blue-600">
              <svg
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                />
              </svg>
            </MenuButton>
          </div>

          <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <MenuItems
              class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-hidden"
            >
              <div class="py-1">
                <MenuItem v-slot="{ active }">
                  <span
                    @click="onStorePost(post.postID)"
                    :class="[
                      active
                        ? 'bg-gray-100 text-gray-900 outline-hidden hover:bg-blue-500 hover:text-white font-bold'
                        : 'text-gray-700 hover:text-white',
                      'block px-4 py-2 text-sm',
                    ]"
                  >
                    Store
                  </span>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                  <span
                    @click="onEditPost(post.postID)"
                    :class="[
                      active
                        ? 'bg-gray-100 text-gray-900 outline-hidden hover:bg-blue-500 hover:text-white font-bold'
                        : 'text-gray-700 hover:text-white',
                      'block px-4 py-2 text-sm',
                    ]"
                  >
                    Edit
                  </span>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                  <span
                    @click="storeDeletePost(post.postID)"
                    :class="[
                      active
                        ? 'bg-gray-100 text-gray-900 outline-hidden hover:bg-blue-500 hover:text-white font-bold'
                        : 'text-gray-700 hover:text-white',
                      'block px-4 py-2 text-sm',
                    ]"
                  >
                    Delete
                  </span>
                </MenuItem>
              </div>
            </MenuItems>
          </transition>
        </Menu>
      </div>
    </div>

    <div class="m-2 mt-4 mb-3 flex justify-center shadow-lg bg-white">
      <p v-for="(image, index) in post.postImage" :key="index">
        <img
          :src="'data:image/png;base64,' + image.imageData"
          alt=""
          class="h-56 w-full object-cover"
        />
      </p>
    </div>

    <div class="bg-white p-4 sm:p-6">
      <div class="grid grid-cols-1">
        <div>
          <h3
            class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100"
          >
            {{ post?.postTitle }}
          </h3>
        </div>
      </div>
      <div class="flex">
        {{ formatDateTime(post?.updatedAT) }}
      </div>

      <div class="flex justify-between p-2">
        <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
          {{ post.postContent }}
        </p>
      </div>
    </div>
    <div class="m-auto p-1 bg-white">
      <div class="grid grid-cols-2">
        <div class="flex justify-center">
          <div class="grid grid-cols-2">
            <div>
              <button
                type="submit"
                class=""
                @click="
                  onEventPostPop(
                    post.postID,
                    authStore.users.userProfile.id,
                    'like'
                  )
                "
              >
                <svg
                  v-if="
                    post.postPops.some(
                      (pop) =>
                        pop.popProfileID === authStore.users.userProfile.id &&
                        pop.popStatus === 'like'
                    )
                  "
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  class="bi bi-hand-thumbs-up-fill text-blue-600 w-[30px] h-[30px]"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"
                  />
                </svg>
                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  class="bi bi-hand-thumbs-up-fill w-[30px] h-[30px]"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"
                  />
                </svg>
              </button>
            </div>
            <div>
              <p class="ml-5 text-2xl font-blod">
                {{
                  post.postPops.filter((pop) => pop.popStatus === "like").length
                }}
              </p>
            </div>
          </div>
        </div>
        <div class="flex justify-center">
          <div class="grid grid-cols-2">
            <div>
              <button
                type="submit"
                class=""
                @click="
                  onEventPostPop(
                    post.postID,
                    authStore.users.userProfile.id,
                    'disLike'
                  )
                "
              >
                <svg
                  v-if="
                    post.postPops.some(
                      (pop) =>
                        pop.popProfileID === authStore.users.userProfile.id &&
                        pop.popStatus === 'disLike'
                    )
                  "
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  class="bi bi-hand-thumbs-down-fill text-red-600 w-[30px] h-[30px]"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"
                  />
                </svg>
                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  class="bi bi-hand-thumbs-down-fill w-[30px] h-[30px]"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"
                  />
                </svg>
              </button>
            </div>
            <div>
              <p class="ml-5 text-2xl font-blod">
                {{
                  post.postPops.filter((pop) => pop.popStatus === "disLike")
                    .length
                }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup>
defineProps({
  post: {
    type: Object,
    required: true,
  },
});

import { ref } from "vue";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import CardProfile from "./user-profiles/CardProfile.vue";

const router = useRouter();
const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
console.log("auth user profile id", authStore.users?.userProfile?.id);
const {
  storeGetPosts,
  storeDeletePost,
  storeEventPostPop,
  storeConfirmStorePost,
} = usePostStore();

const onStorePost = async (postID) => {
  await storeConfirmStorePost(postID);
  await storeGetPosts();
};

const onEditPost = (postID) => {
  console.log("function on edit post ", postID);
  router.push({
    name: "EditPostView",
    params: {
      id: postID,
    },
  });
};

const onEventPostPop = async (postID, profileID, status) => {
  await storeEventPostPop(postID, profileID, status);
  await storeGetPosts();
};

const formatDateTime = (dateTime) => {
  if (!dateTime) return "-";

  const date = new Date(dateTime);

  const year = date.getFullYear() + 543;
  const month = date.getMonth();
  const day = date.getDate();

  const hour = date.getHours().toString().padStart(2, "0");
  const minute = date.getMinutes().toString().padStart(2, "0");
  const second = date.getSeconds().toString().padStart(2, "0");

  const thaiMonths = [
    "มกราคม",
    "กุมภาพันธ์",
    "มีนาคม",
    "เมษายน",
    "พฤษภาคม",
    "มิถุนายน",
    "กรกฎาคม",
    "สิงหาคม",
    "กันยายน",
    "ตุลาคม",
    "พฤศจิกายน",
    "ธันวาคม",
  ];

  return `${day} ${thaiMonths[month]} ${year} เวลา ${hour}:${minute}:${second} น.`;
};
</script>
