<template>
  <div class="p-6 space-y-6">
    <!-- Page Header -->
    <PageHeader title="Manage User Profiles" />

    <!-- User Profiles Table -->
    <div
      v-if="userProfiles.length"
      class="overflow-x-auto bg-white rounded-xl shadow-lg ring-1 ring-gray-200"
    >
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left w-[20%]">#</th>
            <th class="px-4 py-3 text-left w-[25%]">User</th>
            <th class="px-4 py-3 text-left w-[25%]">Profile</th>
            <th class="px-4 py-3 text-left w-[20%]">Contact</th>
            <th class="px-4 py-3 text-left w-[20%]">Status</th>
            <th class="px-4 py-3 text-left w-[15%]">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in userProfiles"
            :key="item.users.id"
            class="border-t odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors"
          >
            <!-- Online Status + Profile Image -->
            <td class="px-4 py-3">
              <div class="flex flex-col items-center">
                <span
                  :class="[
                    item.users?.checkStatusOnline?.status === 'online'
                      ? 'text-green-500'
                      : 'text-gray-400',
                    'font-semibold',
                  ]"
                >
                  {{ item.users?.checkStatusOnline?.status || "offline" }}
                </span>
                <div class="mt-2">
                  <img
                    v-if="item.profiles?.image?.imageData"
                    :src="`data:image/png;base64,${item.profiles.image.imageData}`"
                    class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-200 hover:scale-105 transition-transform duration-300"
                    alt="Profile Image"
                  />
                  <img
                    v-else
                    src="../../assets/images/account-profile.png"
                    class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-200 hover:scale-105 transition-transform duration-300"
                    alt="Default Image"
                  />
                </div>
              </div>
            </td>

            <!-- User Account -->
            <td class="px-4 py-3">
              <div class="space-y-1">
                <p class="font-medium text-gray-800">
                  {{ item.users?.email || "-" }}
                </p>
                <p class="text-gray-500 text-xs">
                  Username: {{ item.users?.username || "-" }}
                </p>
                <span
                  class="inline-block px-2 py-1 text-xs rounded-full"
                  :class="item.users?.status?.name === 'active'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-600'"
                >
                  {{ item.users?.status?.name || "Unknown" }}
                </span>
              </div>
            </td>

            <!-- Profile Info -->
            <td class="px-4 py-3">
              <div class="space-y-1">
                <p class="font-medium">{{ item.profiles?.fullName || "-" }}</p>
                <p class="text-gray-500 text-xs">
                  Nickname: {{ item.profiles?.nickName || "-" }}
                </p>
                <p class="text-gray-400 text-xs">
                  Birthday: {{ formatDateTime(item.profiles?.birthDay) }}
                </p>
              </div>
            </td>

            <!-- Followers and Pops -->
            <td class="px-4 py-3">
              <div class="grid gap-y-1 text-sm">
                <p>
                  <span class="font-semibold">Followers:</span>
                  {{ item.profiles?.followers?.filter((f) => f.status)?.length || 0 }}
                </p>
                <p>
                  <span class="font-semibold">Likes:</span>
                  {{ item.profiles?.pops?.filter((p) => p.status === "like")?.length || 0 }}
                </p>
                <p>
                  <span class="font-semibold">Dislikes:</span>
                  {{ item.profiles?.pops?.filter((p) => p.status === "disLike")?.length || 0 }}
                </p>
              </div>
            </td>

            <td class="px-4 py-3">
                              <!-- Toggle -->
                <div class="flex items-center space-x-2">
                  <span class="text-xs">Disabled</span>
                  <ToggleSwitchStatus
                    v-model="item.users.statusAccount"
                    :userID="item.users.id"
                    @status-changed="onSweetStatusAccount"
                  />
                  <span class="text-xs">Active</span>
                </div>
            </td>

            <!-- Toggle & Actions -->
            <td class="px-4 py-3">
              <div class="flex flex-col items-center gap-2">


                <!-- Actions Dropdown -->
                <Menu as="div" class="relative inline-block">
                  <div>
                    <MenuButton
                      class="inline-flex justify-center rounded-md border border-gray-300 shadow px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                    >
                      Actions
                      <ChevronDownIcon class="ml-2 h-4 w-4" />
                    </MenuButton>
                  </div>
                  <Transition
                    enter="transition ease-out duration-100"
                    enter-from="transform opacity-0 scale-95"
                    enter-to="transform opacity-100 scale-100"
                    leave="transition ease-in duration-75"
                    leave-from="transform opacity-100 scale-100"
                    leave-to="transform opacity-0 scale-95"
                  >
                    <MenuItems
                      class="absolute right-0 mt-2 w-36 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    >
                      <MenuItem>
                        <button
                          @click="onShowDetail(item)"
                          class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                          View Profile
                        </button>
                      </MenuItem>
                      <MenuItem>
                        <button
                          @click="onEditUser(item)"
                          class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                          Edit User
                        </button>
                      </MenuItem>
                      <MenuItem>
                        <button
                          @click="onDeleteAccount(item.users.id)"
                          class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                        >
                          Delete User
                        </button>
                      </MenuItem>
                    </MenuItems>
                  </Transition>
                </Menu>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-else class="text-gray-500 text-center">No user profiles found.</p>
  </div>
</template>


<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { ref, onMounted } from "vue";
import { useManagerBlogStore } from "@/stores/manager-blog";
import PageHeader from "@/components/PageHeader.vue";
import ToggleSwitchStatus from "@/components/user-profiles/ToggleSwitchStatus.vue";

const managerBlogStore = useManagerBlogStore();
const {
  storeManagerGetUserProfiles,
  storeManagerDeleteUser,
  storeManagerUpdateUserStatus,
} = managerBlogStore;

const userProfiles = ref([]);

onMounted(async () => {
  userProfiles.value = await storeManagerGetUserProfiles();
});

// Toggle Account Status API
const onSweetStatusAccount = async (userID, newStatus) => {
  console.log("Toggle user:", userID, "to", newStatus);
  // try {
  //   await storeManagerUpdateUserStatus(
  //     userID,
  //     newStatus ? "active" : "inactive"
  //   );
  //   const user = userProfiles.value.find((u) => u.users.id === userID);
  //   if (user) {
  //     user.users.statusAccount = newStatus ? "active" : "inactive";
  //   }
  // } catch (error) {
  //   console.error("Failed to update status", error);
  // }
};

// Delete Account
const onDeleteAccount = async (userId) => {
  if (confirm("Are you sure you want to delete this user?")) {
    const success = await storeManagerDeleteUser(userId);
    if (success) {
      userProfiles.value = userProfiles.value.filter(
        (u) => u.users.id !== userId
      );
      alert("User deleted successfully.");
    }
  }
};

// Show Profile Detail
const onShowDetail = (item) => {
  console.log("Show Profile", item);
};

// Edit User
const onEditUser = (item) => {
  console.log("Edit User", item);
};

// Format DateTime
const formatDateTime = (dateTime) => {
  if (!dateTime) return "-";
  const date = new Date(dateTime);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    // hour: "2-digit",
    // minute: "2-digit",
  };
  return date.toLocaleDateString("th-TH", options);
};
</script>
