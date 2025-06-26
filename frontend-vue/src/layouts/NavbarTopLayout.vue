<template>
  <Disclosure
    as="nav"
    class="bg-white"
    v-slot="{ open }"
    v-if="authStore.users"
  >
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Left: Logo + Desktop Nav -->
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
              <button
                v-for="item in navigation"
                :key="item.title"
                @click="handleNavigation(item)"
                :class="[
                  item.current
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 hover:bg-gray-700 hover:text-white',
                  'rounded-md px-3 py-2 text-sm font-medium',
                ]"
              >
                {{ item.title }}
              </button>
            </div>
          </div>
        </div>

        <!-- Right: Notification + Profile -->
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            <!-- Notification -->
            <button
              type="button"
              class="relative rounded-full bg-white p-1 text-gray-900 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white"
            >
              <span class="sr-only">View notifications</span>
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
                class="relative flex max-w-xs items-center rounded-full bg-white text-sm focus:ring-2 focus:ring-offset-2 focus:ring-white"
              >
                <span class="sr-only">Open user menu</span>
                <img
                  class="size-8 rounded-full"
                  :src="
                    authStore.users?.profileImage?.imageData
                      ? 'data:image/png;base64,' +
                        authStore.users.profileImage.imageData
                      : imageDefault
                  "
                  alt="User"
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
                    :key="item.title"
                    v-slot="{ active }"
                  >
                    <button
                      @click="handleNavigation(item)"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700"
                    >
                      {{ item.title }}
                    </button>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="-mr-2 flex md:hidden">
          <DisclosureButton
            class="relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-700 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white"
          >
            <span class="sr-only">Open main menu</span>
            <svg
              class="block h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </DisclosureButton>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Panel -->
    <DisclosurePanel class="md:hidden">
      <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        <button
          v-for="item in navigation"
          :key="item.title"
          @click="handleNavigation(item)"
          :class="[
            item.current
              ? 'bg-gray-900 text-white'
              : 'text-gray-700 hover:bg-gray-700 hover:text-white',
            'block rounded-md px-3 py-2 text-base font-medium',
          ]"
        >
          {{ item.title }}
        </button>
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
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
const { storeLogout } = useAuthStore();
const router = useRouter();

const imageDefault = "/default-profile.png";

// Main menu
const navigation = [
  { title: "Home", name: "HomeView", current: true },
  { title: "Create Post", name: "CreatePostView", current: false },
  { title: "Store Post", name: "StorePostsView", current: false },
  { title: "Shop Reward", name: "ShopRewardView", current: false },
  { title: "Dashboard Manager", name: "DashboardManagerView", current: false },
];

// Profile dropdown
const userNavigation = [
  { title: "Dashboard Profile", name: "DashboardProfileView" },
  { title: "Store Post", name: "StorePostsView" },
  { title: "Sign out", name: "SignOut" },
];

// Unified navigation handler
const handleNavigation = (item) => {
  switch (item.name) {
    case "HomeView":
    case "CreatePostView":
    case "ShopRewardView":
    case "DashboardManagerView":
      router.push({ name: item.name });
      break;
    case "DashboardProfileView":
      router.push({
        name: item.name,
        params: { id: authStore.users?.userProfile?.id },
      });
      break;
    case "StorePostsView":
      router.push({
        name: item.name,
        params: { profileID: authStore.users?.userProfile?.id },
      });
      break;
    case "SignOut":
      onLogout();
      break;
    default:
      router.push({ name: "PageNotFoundView" });
  }
};

const onLogout = async () => {
  await storeLogout();
};
</script>
