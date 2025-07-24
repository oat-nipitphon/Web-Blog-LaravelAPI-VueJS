<template>
    <Menu as="div" class="m-auto font-bold">
      <div>
        <MenuButton class="inline-flex text-gray-900 hover:text-red-500">
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
                @click="onStorePost(post?.postID)"
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
                @click="onEditPost(post?.postID)"
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
                @click="onDeletePost(post?.postID)"
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
</template>
<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

const props = defineProps({
  post: {
    type: Object,
    default: () => ({}), // ✅ ป้องกัน undefined
  },
  onStorePost: {
    type: Function,
    default: () => {}, // ✅ ฟังก์ชันว่างๆ fallback
  },
  onEditPost: {
    type: Function,
    default: () => {},
  },
  onDeletePost: {
    type: Function,
    default: () => {},
  },
});
</script>
