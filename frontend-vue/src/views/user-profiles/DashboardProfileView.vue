<template>
  <div>
    <div
      class="max-w-6xl mx-auto mt-10 p-8 bg-white border border-gray-200 rounded-2xl shadow-lg"
      v-if="profileDetails"
    >
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- LEFT COLUMN -->
        <div
          class="flex flex-col items-center bg-gray-50 p-6 rounded-xl shadow-sm"
        >
          <!-- Profile Image -->
          <div
            class="w-40 h-40 overflow-hidden rounded-full shadow-md border border-gray-300"
          >
            <img
              :src="
                'data:image/png;base64,' + profileDetails.profileImage.imageData
              "
              alt="ProfileImage"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Upload Image -->
          <div class="mt-4 w-full">
            <UploadImage :profileID="profileDetails?.profile?.id" />
          </div>

          <!-- Contact Card -->
          <div
            class="mt-6 w-full bg-white p-6 rounded-xl border border-gray-200 shadow"
          >
            <CardShowContacts
              :profileID="profileDetails?.profile?.id"
              :contacts="profileDetails?.profileContacts"
            />
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="bg-gray-50 p-6 rounded-xl shadow-inner">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">รายละเอียด</h3>
            <CardEditUserModal
              :user="profileDetails?.user"
              :userStatus="userStatus"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div class="text-gray-500">ชื่อผู้ใช้:</div>
            <div class="text-gray-800 font-medium">
              {{ profileDetails?.user?.username }}
            </div>

            <div class="text-gray-500">อีเมล์:</div>
            <div class="text-gray-800 font-medium">
              {{ profileDetails?.user?.email }}
            </div>

            <div class="text-gray-500">ชื่อ-นามสกุล:</div>
            <div class="text-gray-800 font-medium leading-tight">
              <p>{{ profileDetails?.profile?.titleName }}</p>
              <p>{{ profileDetails?.profile?.firstName }}</p>
              <p>{{ profileDetails?.profile?.lastName }}</p>
            </div>

            <div class="text-gray-500">ชื่อเล่น:</div>
            <div class="text-gray-800 font-medium">
              {{ profileDetails?.profile?.nickName }}
            </div>

            <div class="text-gray-500">วัน เดือน ปีเกิด:</div>
            <div class="text-gray-800 font-medium">
              {{ formatBirthDay }}
            </div>

            <div class="text-gray-500">อายุ:</div>
            <div class="text-gray-800 font-medium">
              {{ formatAge }}
            </div>
          </div>

          <div class="flex justify-end mt-6">
            <CardEditProfileModal :profile="profileDetails?.profile" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";
import { useStoreUserProfile } from "@/stores/user-profile";

import UploadImage from "@/components/user-profiles/UploadImage.vue";
import CardShowContacts from "@/components/user-profiles/CardShowContacts.vue";

import CardEditUserModal from "@/components/user-profiles/CardEditUserModal.vue";
import CardEditProfileModal from "@/components/user-profiles/CardEditProfileModal.vue";

const route = useRoute();
const profileDetails = ref(null);
const userStatus = ref([]);

const storeUserProfile = useStoreUserProfile();
const { userProfile } = storeToRefs(storeUserProfile);
console.log(storeUserProfile.userProfile);
const { storeGetUserStatus, storeGetUserProfile } = useStoreUserProfile();

onMounted(async () => {
  profileDetails.value = await storeGetUserProfile(route.params.id);
  userStatus.value = await storeGetUserStatus();
});

const formatBirthDay = computed(() => {
  if (!profileDetails.value.profile?.birthDay) {
    return "Day-Month-Year";
  }
  const birthDate = new Date(profileDetails.value.profile?.birthDay);
  const day = birthDate.getDate().toString().padStart(2, "0");
  const month = (birthDate.getMonth() + 1).toString().padStart(2, "0");
  const year = birthDate.getFullYear() + 543;
  return `${day} / ${month} / ${year}`;
});

const formatAge = computed(() => {
  if (!profileDetails.value.profile?.birthDay) return "Age not available";
  const birthDate = new Date(profileDetails.value.profile?.birthDay);
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
});
</script>