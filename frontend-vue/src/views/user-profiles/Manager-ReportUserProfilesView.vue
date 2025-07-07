<template>
  <div class="p-6 space-y-6">
    <!-- Page Header -->
    <PageHeader title="Manage User Profiles" />

    <!-- User Profiles Table -->
    <div v-if="userProfiles.length" class="overflow-x-auto bg-white rounded-xl shadow-lg ring-1 ring-gray-200">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left w-[auto]">#</th>
            <th class="px-4 py-3 text-left w-[30%]">User</th>
            <th class="px-4 py-3 text-left w-[30%]">Profile</th>
            <th class="px-4 py-3 text-left w-[30%]">Contact</th>
            <th class="px-4 py-3 text-left w-[auto]">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in userProfiles" :key="item.users.id"
            class="border-t odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
            <!-- Online Status + Profile Image -->
            <td class="px-4 py-3">
              <div class="flex flex-col items-center">
                <span :class="[
                  item.users?.checkStatusOnline?.status === 'online'
                    ? 'text-green-500'
                    : 'text-gray-400',
                  'font-semibold',
                ]">
                  {{ item.users?.checkStatusOnline?.status || "offline" }}
                </span>
                <div class="mt-2">
                  <img v-if="item.profiles?.image?.imageData"
                    :src="`data:image/png;base64,${item.profiles.image.imageData}`"
                    class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-200 hover:scale-105 transition-transform duration-300"
                    alt="Profile Image" />
                  <img v-else src="../../assets/images/account-profile.png"
                    class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-200 hover:scale-105 transition-transform duration-300"
                    alt="Default Image" />
                </div>
                <div class="flex justify-center items-center">
                    <UploadImageProfileModal :profileID="item.profiles.id" />
                </div>
              </div>
            </td>

            <!-- User Account -->
            <td class="px-4 py-3">
              <div class="space-y-1">
                <p class="font-medium text-gray-700 text-xs">
                  {{ item.users?.email || "-" }}
                </p>
                <p class="text-gray-700 text-xs font-medium">
                  Username: {{ item.users?.username || "-" }}
                </p>
                <span class="inline-block px-2 py-1 text-xs rounded-full font-medium" :class="item.users?.status?.name === 'active'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-600'
                  ">
                  {{ item.users?.status?.name || "Unknown" }}
                </span>
                <EditUserModal :user="item.users" :userStatus="userStatus" />
              </div>
            </td>

            <!-- Profile Info -->
            <td class="px-4 py-3">
              <div class="space-y-1">
                <p class="font-medium">{{ item.profiles?.fullName || "-" }}</p>
                <p class="text-gray-700 text-xs font-medium">
                  Nickname: {{ item.profiles?.nickName || "-" }}
                </p>
                <p class="text-gray-700 text-xs font-medium">
                  Birthday: {{ formatDateTime(item.profiles?.birthDay) }}
                </p>
                <p class="text-gray-700 text-xs font-medium">
                  Age: {{ formatAge(item.profiles?.birthDay) }}
                </p>
                <EditProfileModal :profile="item.profiles" />
              </div>
            </td>

            <!-- Followers and Pops -->
            <td class="px-4 py-3">
              <div class="grid gap-y-1 text-sm">
                <p>
                  <span class="font-medium text-xs text-gray-700">Followers:</span>
                  {{
                    item.profiles?.followers?.filter((f) => f.status)?.length ||
                    0
                  }}
                </p>
                <p>
                  <span class="font-medium text-xs text-gray-700">Likes:</span>
                  {{
                    item.profiles?.pops?.filter((p) => p.status === "like")
                      ?.length || 0
                  }}
                </p>
                <p>
                  <span class="font-medium text-xs text-gray-700">Dislikes:</span>
                  {{
                    item.profiles?.pops?.filter((p) => p.status === "disLike")
                      ?.length || 0
                  }}
                </p>
              </div>
            </td>

            <!-- Toggle & Actions -->
            <td class="px-4 py-3">
              <div class="grid grid-rows-2">
                <div class="flex items-center space-x-2">
                  <span class="text-xs">Disabled</span>
                  <ToggleSwitchStatus v-model="item.users.statusAccount" :userID="item.users.id"
                    @status-changed="onSweetStatusAccount" />
                  <span class="text-xs">Active</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                  <!-- Actions Dropdown -->
                  <button @click="onDeleteAccount(item.users.id)" class="block w-full px-4 py-2 text-sm text-red-600 bg-white
                  hover:text-white
                  hover:bg-red-500 
                  font-bold
                  ">
                    Delete Account
                  </button>
                </div>
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
import { useStoreUserProfile } from "@/stores/user-profile";
import PageHeader from "@/components/PageHeader.vue";
import ToggleSwitchStatus from "@/components/user-profiles/ToggleSwitchStatus.vue";
import EditUserModal from "@/components/user-profiles/EditUserModal.vue";
import EditProfileModal from "@/components/user-profiles/EditProfileModal.vue";
import UploadImageProfileModal from "@/components/user-profiles/UploadImageProfileModal.vue";

const managerBlogStore = useManagerBlogStore();
const { storeManagerGetUserProfiles, storeManagerUpdateStatusAccount } = managerBlogStore;

const { storeGetUserStatus } = useStoreUserProfile();

const userStatus = ref([]);
const userProfiles = ref([]);
const selectedUser = ref(null);
const selectedProfile = ref(null);

onMounted(async () => {
  userProfiles.value = await storeManagerGetUserProfiles();
  userStatus.value = await storeGetUserStatus();
});


// Toggle Account Status API
const onSweetStatusAccount = async (userID, newStatus) => {
  console.log("Toggle user:", userID, "to", newStatus);
  try {
   
    const formData = new FormData();
    formData.append('id', userID);
    formData.append('status', newStatus);


    const success = await storeManagerUpdateStatusAccount(formData);

    if (success) {
      const user = userProfiles.value.find((u) => u.users.id === userID);
      if (user) {
        user.users.statusAccount = newStatus ? "active" : "inactive";
      }
    }

  } catch (error) {
    console.error("Failed to update status", error);
  }
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

  // const birthDate = new Date(profileDetails.value.profile?.birthDay);
  // const day = birthDate.getDate().toString().padStart(2, "0");
  // const month = (birthDate.getMonth() + 1).toString().padStart(2, "0");
  // const year = birthDate.getFullYear() + 543;
  // return `${day} / ${month} / ${year}`;

};

function formatAge(profileBirthDay) {
  if (!profileBirthDay) return "Age not available";
  const birthDate = new Date(profileBirthDay);
  const currentDate = new Date();
  let calculatedAge = currentDate.getFullYear() - birthDate.getFullYear();
  const monthDifference = currentDate.getMonth() - birthDate.getMonth();
  if (
    monthDifference < 0 ||
    (monthDifference === 0 && currentDate.getDate() < birthDate.getDate())
  ) {
    calculatedAge--;
  }
  return `Age ${calculatedAge} years`;
};

</script>
