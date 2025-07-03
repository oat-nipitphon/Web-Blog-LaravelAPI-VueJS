<template>
  <div class="p-6 bg-gray-50 min-h-screen">
<PageHeader title="Manager Account" />

    <!-- User Profiles Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="text-center px-4 py-3 font-semibold">#</th>
            <th class="text-center px-4 py-3 font-semibold">Status</th>
            <th class="text-center px-4 py-3 font-semibold">Image</th>
            <th class="text-left px-4 py-3 font-semibold">Profile Details</th>
            <th class="text-center px-4 py-3 font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="border-b hover:bg-gray-50 transition"
            v-for="n in 5"
            :key="n"
          >
            <td class="text-center px-4 py-3 font-medium">{{ n }}</td>
            <td class="text-center px-4 py-3">
              <span class="text-green-600 font-semibold">online</span>
              <div class="text-sm text-gray-500">active</div>
            </td>
            <td class="text-center px-4 py-3">
              <img
                src="https://via.placeholder.com/80"
                class="w-16 h-16 rounded-full m-auto object-cover"
                alt="profile image"
              />
            </td>
            <td class="px-4 py-3">
              <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                <span class="font-semibold">Email:</span
                ><span>user{{ n }}@example.com</span>
                <span class="font-semibold">Name:</span
                ><span>User {{ n }}</span>
                <span class="font-semibold">Tel:</span
                ><span>098-123456{{ n }}</span>
                <span class="font-semibold">Age:</span><span>{{ 20 + n }}</span>
              </div>
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
      </table>
    </div>
  </div>
</template>
<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useManagerBlogStore } from "@/stores/manager-blog";
import PageHeader from "@/components/PageHeader.vue";

const managerBlogStore = useManagerBlogStore();
const { storeManagerGetUserProfiles } = managerBlogStore;

const userProfiles = ref([]);

onMounted(async () => {
  userProfiles.value = await storeManagerGetUserProfiles();
  console.log('manager report user profiles view ', userProfiles.value);
});



</script>

