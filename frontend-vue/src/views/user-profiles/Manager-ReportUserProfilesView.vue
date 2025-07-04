<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <PageHeader title="Manager Account" />

    <!-- User Profiles Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="text-center px-4 py-3 font-semibold">#</th>
            <th class="text-center px-4 py-3 font-semibold">Account</th>
            <th class="text-center px-4 py-3 font-semibold">Profile</th>
            <th class="text-center px-4 py-3 font-semibold">Details</th>
            <th class="text-center px-4 py-3 font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(userProfile, index) in userProfiles"
            :key="userProfile.users.id"
            class="border-b hover:bg-gray-50 transition"
          >
            <!-- Profile Image & Status -->
            <td class="text-center px-4 py-3 font-medium">
              <div class="grid grid-rows-2">
                <span
                  :class="[
                    userProfile.users?.checkStatusOnline?.status === 'online'
                      ? 'text-green-600'
                      : 'text-gray-400',
                    'font-semibold m-auto',
                  ]"
                >
                  {{
                    userProfile.users?.checkStatusOnline?.status || "offline"
                  }}
                </span>
                <div class="p-2 mt-2 flex justify-center items-center">
                  <img
                    v-if="userProfile.profiles?.image?.imageData"
                    :src="`data:image/png;base64,${userProfile.profiles.image.imageData}`"
                    class="w-16 h-16 rounded-full object-cover"
                    alt="Profile Image"
                  />
                  <img
                    v-else
                    src="../../assets/images/account-profile.png"
                    class="w-16 h-16 rounded-full object-cover"
                    alt="Default Image"
                  />
                </div>
              </div>
            </td>

            <!-- User Account -->
            <td class="px-4 py-3">
              <div class="grid gap-y-1">
                <span class="font-semibold">Email:</span>
                <span>{{ userProfile.users?.email || "-" }}</span>
                <span class="font-semibold">Username:</span>
                <span>{{ userProfile.users?.username || "-" }}</span>
                <span class="font-semibold">Role:</span>
                <span>{{ userProfile.users?.status?.name || "-" }}</span>
              </div>
            </td>

            <!-- Profile Info -->
            <td class="px-4 py-3">
              <div class="grid gap-y-1">
                <span class="font-semibold">Full Name:</span>
                <span>{{ userProfile.profiles?.fullName || "-" }}</span>
                <span class="font-semibold">Nickname:</span>
                <span>{{ userProfile.profiles?.nickName || "-" }}</span>
                <span class="font-semibold">Birth Day:</span>
                <span>{{
                  formatDateTime(userProfile.profiles?.birthDay)
                }}</span>
              </div>
            </td>

            <!-- Details -->
            <td class="px-4 py-3">
              <div class="grid gap-y-2">
                <!-- Followers -->
                <div class="grid grid-cols-2 gap-1">
                  <span class="font-semibold">Followers:</span>
                  <span>
                    {{
                      userProfile.profiles?.followers?.filter((f) => f.status)
                        ?.length || 0
                    }}
                  </span>
                </div>

                <!-- Pops -->
                <div class="grid grid-cols-2 gap-1">
                  <span class="font-semibold">Pops Like:</span>
                  <span>
                    {{
                      userProfile.profiles?.pops?.filter(
                        (p) => p.status === "like"
                      )?.length || 0
                    }}
                  </span>
                  <span class="font-semibold">Pops Dislike:</span>
                  <span>
                    {{
                      userProfile.profiles?.pops?.filter(
                        (p) => p.status === "disLike"
                      )?.length || 0
                    }}
                  </span>
                </div>

                <!-- Contacts -->
                <div class="flex justify-center items-center space-x-1">
                  <div
                    v-for="(contact, cIndex) in userProfile.profiles?.contacts"
                    :key="cIndex"
                  >
                    <img
                      v-if="contact?.imageData"
                      :src="`data:image/png;base64,${contact.imageData}`"
                      class="w-5 h-5 rounded-full object-cover"
                      alt="Contact Icon"
                    />
                  </div>
                </div>
              </div>
            </td>

            <!-- Actions -->
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
                          @click="showProfile(userProfile)"
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
                          @click="editProfile(userProfile)"
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
                          @click="deleteProfile(userProfile.users.id)"
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
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useManagerBlogStore } from "@/stores/manager-blog";
import PageHeader from "@/components/PageHeader.vue";

const router = useRouter();
const managerBlogStore = useManagerBlogStore();
const { storeManagerGetUserProfiles, storeManagerDeleteUser } =
  managerBlogStore;

const userProfiles = ref([]);

onMounted(async () => {
  userProfiles.value = await storeManagerGetUserProfiles();
});

// Format DateTime
const formatDateTime = (dateTime) => {
  if (!dateTime) return "-";
  const date = new Date(dateTime);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleDateString("th-TH", options);
};

// Actions
const showProfile = (profile) => {
  console.log("Show Profile", profile);
  router.push({ name: "ShowProfileView", params: { id: profile.users.id } });
};

const editProfile = (profile) => {
  console.log("Edit Profile", profile);
  router.push({ name: "EditProfileView", params: { id: profile.users.id } });
};

const deleteProfile = async (userId) => {
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
</script>
