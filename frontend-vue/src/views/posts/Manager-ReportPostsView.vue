<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <PageHeader title="Manager Post" />

    <!-- Posts Table -->
    <div class="bg-white rounded-xl shadow">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="text-center px-4 py-3 font-semibold">#</th>
            <th class="text-left px-4 py-3 font-semibold">Title</th>
            <th class="text-left px-4 py-3 font-semibold">Owner</th>
            <th class="text-center px-4 py-3 font-semibold">Type</th>
            <th class="text-center px-4 py-3 font-semibold">Status</th>
            <th class="text-center px-4 py-3 font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody v-if="posts.length > 0">
          <tr
            class="border-b hover:bg-gray-50 transition"
            v-for="(post, index) in posts"
            :key="post.postID"
          >
            <td class="text-center px-4 py-3">{{ index + 1 }}</td>
            <td class="px-4 py-3 font-medium text-gray-800">
              Post Title {{ post.postTitle }}
            </td>
            <td class="px-4 py-3">User {{ post.user?.username }}</td>
            <td class="text-center px-4 py-3">{{ post.postType?.typeName }}</td>
            <td class="text-center px-4 py-3">
              <span
                class="px-3 py-1 rounded-full text-xs font-semibold"
                :class="
                  post.postStatus === 'active'
                    ? 'bg-green-100 text-green-700'
                    : post.postStatus === 'store'
                    ? 'bg-yellow-100 text-yellow-700'
                    : post.postStatus === 'disable'
                    ? 'bg-red-100 text-red-700'
                    : 'bg-red-100 text-gray-700'
                "
              >
                {{
                  post.postStatus === "active"
                    ? "active"
                    : post.postStatus === "store"
                    ? "store"
                    : post.postStatus === "disable"
                    ? "disable"
                    : "null"
                }}
              </span>
            </td>
            <td class="text-center px-4 py-3">
              <Menu as="div" class="relative inline-block text-left">
                <div>
                  <MenuButton
                    class="inline-flex justify-center items-center px-4 py-2 bg-white border rounded-md shadow text-sm font-medium hover:bg-gray-50"
                  >
                    Options
                    <ChevronDownIcon class="ml-2 h-5 w-5 text-gray-500" />
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
                    class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <div class="py-1">
                      <MenuItem v-slot="{ active }">
                        <button
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-gray-700',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Show
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-gray-700',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Edit
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-red-600',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Delete
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr class="text-center">
            <td class="text-2xl font-bold text-red-500">Post repsonse null</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useManagerBlogStore } from "@/stores/manager-blog";
import PageHeader from "@/components/PageHeader.vue";


const managerBlogStore = useManagerBlogStore();
const { storeManagerGetPosts } = managerBlogStore;

const posts = ref([]);

onMounted(async () => {
  posts.value = await storeManagerGetPosts();
});
</script>
