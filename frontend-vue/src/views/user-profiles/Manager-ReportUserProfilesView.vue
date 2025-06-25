<!-- <script setup>
import { ref, onMounted, } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAdminUserProfileStore } from '@/stores/admin.user.profile'
const fallbackImage = 'https://via.placeholder.com/150?text=No+Image'

const { adminAPIGETuserProfiles, adminDeleteUserProfile, storeAdminAPIDeleteUserProfile } =
  useAdminUserProfileStore()

const router = useRouter()
const userProfiles = ref([])
const openDropdown = ref(null);


const formatBrithDay = brithDay => {
  const birthDate = new Date(brithDay)
  const currentDate = new Date()
  let calculatedAge = currentDate.getFullYear() - birthDate.getFullYear()
  const monthDifference = currentDate.getMonth() - birthDate.getMonth()
  if (
    monthDifference < 0 ||
    (monthDifference === 0 && currentDate.getDate() < birthDate.getDate())
  ) {
    calculatedAge--
  }
  return calculatedAge
}

const onShow = async (userProfile) => {
  console.log('onShow', userProfile);
}

const onEdit = async (userProfile) => {
  console.log('onEdit', userProfile.id);
  router.push({
    name: 'AdminEditUserProfileView',
    params: {
      userProfileID: userProfile.id
    }
  });
}

const onDelete = async (userProfile) => {
  console.log('onDelete', userProfile);
  await adminDeleteUserProfile(userProfile)
  userProfiles.value = await adminAPIGETuserProfiles()
}


onMounted(async () => {
  userProfiles.value = await adminAPIGETuserProfiles()
})

</script>

<template>
  <div class="bg-white rounded-lg shadow-lg p-3 w-auto h-auto">
    <div class="rounded border border-gray-300 shadow-sm">
      <table class="min-w-full divide-y-2 divide-gray-200">
        <thead class="ltr:text-left rtl:text-right">
          <tr class="*:font-medium *:text-gray-900">
            <th class="px-3 py-2 whitespace-nowrap">#</th>
            <th class="px-3 py-2 whitespace-nowrap">Status</th>
            <th class="px-3 py-2 whitespace-nowrap">Image</th>
            <th class="px-3 py-2 whitespace-nowrap">Profile Detail</th>
            <th class="px-3 py-2 whitespace-nowrap">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200" v-if="userProfiles.length > 0">
          <tr class="*:text-gray-900 *:first:font-medium" v-for="(userProfile, index) in userProfiles" :key="index">
            <td class="px-3 py-2 whitespace-nowrap">{{ index }}</td>
            <td class="px-3 py-2 whitespace-nowrap">
              <div class="flex flex-col">
                <span v-if="userProfile?.userLogin?.status_login === 'online'" class="text-green-600 font-semibold">{{
                  userProfile?.userLogin?.status_login }}</span>
                <span v-else class="text-red-600 font-semibold">{{ userProfile?.userLogin?.status_login }}</span>
                <span class="text-gray-500">{{ userProfile?.userStatus?.status_name }}</span>
              </div>
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
              <div v-for="image in userProfile.userImage" :key="image.id">
                <img :src="image.imageData ? 'data:image/png;base64,' + image.imageData : fallbackImage"
                  class="w-16 h-16 object-cover rounded-full border" alt="Profile Image" />
              </div>
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
              <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-gray-700">
                <span class="font-semibold">Email:</span><span>{{ userProfile?.email }}</span>
                <span class="font-semibold">Name:</span><span>{{ userProfile?.userProfile?.full_name }}</span>
                <span class="font-semibold">Tel:</span><span>{{ userProfile?.userProfile?.tel_phone }}</span>
                <span class="font-semibold">Age:</span><span>{{ formatBrithDay(userProfile?.userProfile?.birth_day)
                  }}</span>
              </div>
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
              <div class="relative inline-block">
                <button @click="openDropdown = userProfile.id"
                  class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded text-xs">
                  Actions
                </button>
                <div v-if="openDropdown === userProfile.id" @click.outside="openDropdown = null"
                  class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg z-50">
                  <ul class="py-1 text-sm text-gray-700">
                    <li><button @click="onShow(userProfile)"
                        class="block w-full px-4 py-2 hover:bg-gray-100">Show</button></li>
                    <li><button @click="onEdit(userProfile.id)"
                        class="block w-full px-4 py-2 hover:bg-gray-100">Edit</button></li>
                    <li><button @click="storeAdminAPIDeleteUserProfile(userProfile.id)"
                        class="block w-full px-4 py-2 text-red-600 hover:bg-gray-100">Delete</button></li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="5" class="text-center py-5 text-gray-500 font-semibold">
              No user profile data available.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template> -->
<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
</script>
<template>
  <div class="m-auto">
    <h1>Manager Report User Profile VIew</h1>
  </div>
</template>