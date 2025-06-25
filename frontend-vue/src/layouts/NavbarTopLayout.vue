<template>
  <Disclosure
    as="nav"
    class="bg-white"
    v-slot="{ open }"
    v-if="authStore.users"
  >
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <!-- Logo -->
          <div class="shrink-0">
            <RouterLink :to="{ name: 'HomeView' }">
              <img
                class="size-8"
                src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                alt="Your Company"
              />
            </RouterLink>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <RouterLink
                v-for="item in navigation"
                :key="item.name"
                :to="item.to"
                @click="setActive(item)"
                :class="[
                  item.current
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                  'rounded-md px-3 py-2 text-sm font-medium',
                ]"
              >
                {{ item.name }}
              </RouterLink>
            </div>
          </div>
        </div>

        <!-- Profile Menu -->
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            <!-- Notification -->
            <button
              type="button"
              class="relative rounded-full bg-white p-1 text-gray-900 hover:text-gray-900 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden"
            >
              <span class="sr-only">View notifications</span>
              <!-- 
                Icon or Image Notification
              -->
              <svg
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
                  d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"
                />
              </svg>
            </button>

            <!-- Profile Dropdown -->
            <Menu as="div" class="relative ml-3">
              <MenuButton
                class="relative flex max-w-xs items-center rounded-full bg-white text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white"
              >
                <span class="sr-only">Open user menu</span>
                <img
                  class="size-8 rounded-full"
                  :src="
                    'data:image/png;base64,' +
                    authStore.users?.profileImage?.imageData || imageDefault
                  "
                  alt=""
                />
              </MenuButton>
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems
                  class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                  <MenuItem
                    v-for="item in userNavigation"
                    :key="item.name"
                    v-slot="{ active }"
                  >
                    <button
                      @click="handleUserNavigation(item)"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700"
                    >
                      {{ item.name }}
                    </button>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="-mr-2 flex md:hidden">
          <DisclosureButton
            class="relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-700 hover:bg-white hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-white"
          >
            <span class="sr-only">Open main menu</span>
            <img
              class="size-8 rounded-full"
              :src="
                'data:image/png;base64,' +
                authStore.users?.profileImage?.imageData || imageDefault
              "
              alt=""
            />
          </DisclosureButton>
        </div>
      </div>
    </div>

    <!-- Mobile Panel -->
    <DisclosurePanel class="md:hidden">
      <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        <RouterLink
          v-for="item in navigation"
          :key="item.name"
          :to="item.to"
          @click="setActive(item)"
          :class="[
            item.current
              ? 'bg-gray-900 text-white'
              : 'text-gray-300 hover:bg-gray-700 hover:text-white',
            'block rounded-md px-3 py-2 text-base font-medium',
          ]"
        >
          {{ item.name }}
        </RouterLink>
      </div>
    </DisclosurePanel>
  </Disclosure>
</template>

<script setup>
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from "@headlessui/vue";
import { ref } from "vue";
import { useRouter, RouterLink } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import imageDefault from "@/assets/images/account-profile.png";

const router = useRouter();
const authStore = useAuthStore();

const { users } = storeToRefs(authStore);
const { storeLogout } = authStore;

const navigation = ref([
  { name: "Home", to: "/HomeView", current: true },
  { name: "Create Post", to: "/CreatePostView", current: false },
  { name: "Store Post", to: "/StorePostsView", current: false },
  { name: "Reward Shop", to: "/RewardShopView", current: false },
  { name: "Admin Manager", to: "/AdminManagerView", current: false },
  { name: "Manager Reward", to: "/ManagerReportRewardView", current: false },
]);

const userNavigation = [
  { name: "Dashboard Profile", to: "/DashboardProfileView" },
  { name: "Store Post", to: "/StorePostsView" },
  { name: "Sign out", to: "#" },
];

function setActive(clickedItem) {
  navigation.value.forEach((item) => {
    item.current = item.name === clickedItem.name;
  });
}

function handleUserNavigation(item) {
  if (item.name === "Sign out") {
    onLogout();
  } else {
    router.push({
      name: item.name.replace(/\s/g, "") + "View", // e.g., DashboardProfileView
      params: {
        id: authStore.users.userProfile?.id,
        profileID: authStore.users.userProfile?.id,
      },
    });
  }
}

async function onLogout() {
  console.log("Logging out...");
  await storeLogout();
}
</script>
