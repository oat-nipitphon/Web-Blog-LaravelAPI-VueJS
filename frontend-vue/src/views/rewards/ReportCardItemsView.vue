<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
</script>
<template>
  <div class="m-auto">
    <h1>Report Card Items View</h1>
  </div>
</template>
<!-- <script setup>
import axiosAPI from '@/services/axiosAPI'
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useRewardStore } from '@/stores/reward'

const route = useRoute()
const authStore = useAuthStore()
const { getReportRewards, cancelReward } = useRewardStore()
const rewards = ref([])
const userID = ref(authStore.storeUser?.user_login.id)
const currentPage = ref(1)
const itemsPerPage = ref(5)

console.log('userID', userID.value)

onMounted(async () => {
    try {
        const response = await getReportRewards(route.params.userID)
        if (response && response.counters) {
            rewards.value = response.counters
            console.log('report reward view', rewards.value)
        } else {
            console.log('No rewards found')
        }
    } catch (error) {
        console.error('Error fetching rewards:', error)
    }
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleString('th-TH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    })
}

const onSertReward = async (rewardID, userID) => {
    console.log('Function onSertReward', rewardID, userID)
}

const onRecoverReward = async (rewardID, userID) => {
    console.log('Function onRecoverReward', rewardID, userID)
}

const onCancelReward = async (itemID) => {
    try {
        const res = await axiosAPI.post(`/api/cartItems/cancel_reward/${itemID}`, {}, {
            headers: {
                authorization: `Bearer ${localStorage.getItem('token')}`,
            }
        })

        if (res.status === 201) {
            rewards.value = rewards.value.filter(reward => reward.id !== itemID);
        } else {
            console.log('Cancel reward failed for itemID', itemID, res)
        }
    } catch (error) {
        console.error('Error canceling reward:', error)
    }
}

const totalPages = computed(() => {
    return Math.ceil(rewards.value.length / itemsPerPage.value)
})

const paginatedRewards = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    return rewards.value.slice(start, start + itemsPerPage.value)
})

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}

</script>

<template>
    <div class="bg-white rounded-md shadow-lg p-5">
        <div class="overflow-x-auto mx-auto p-6">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gray-200 text-gray-700 uppercase">
                    <tr>
                        <th class="p-3 text-left">Counter</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Image</th>
                        <th class="p-3 text-left">Point</th>
                        <th class="p-3 text-left">Detail</th>
                        <th class="p-3 text-left">Date Time</th>
                        <th class="p-3 text-left">Event</th>
                    </tr>
                </thead>
                <tbody v-if="paginatedRewards.length > 0">
                    <tr v-for="(reward, index) in paginatedRewards" :key="reward.id"
                        class="border-t border-gray-200 hover:bg-gray-100">
                        <td class="p-3 text-center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                        <td class="p-3">
                            <span class="badge bg-primary text-white px-2 py-1 rounded-full text-xs">status
                                reward</span>
                        </td>
                        <td class="p-3">
                            <div v-if="reward.rewards && reward.rewards.length > 0">
                                <img v-for="(image, idx) in reward.rewards[0].images" :key="idx"
                                    :src="'data:image/png;base64,' + image.image_data"
                                    class="w-16 h-16 object-cover rounded-lg mx-auto" alt="Reward Image" />
                            </div>
                        </td>
                        <td class="p-3 text-center">{{ reward.rewards[0]?.point }}</td>
                        <td class="p-3">{{ reward.detail }}</td>
                        <td class="p-3">{{ formatDate(reward.created_at) }}</td>
                        <td class="p-3">
                            <div class="relative">
                                <button type="button"
                                    class="btn btn-sm btn-primary px-4 py-2 rounded-lg border border-gray-300 hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Event
                                </button>
                                <ul
                                    class="dropdown-menu absolute right-0 w-48 bg-white shadow-lg rounded-lg border border-gray-300 mt-2">
                                    <li class="dropdown-item p-2 hover:bg-gray-100 cursor-pointer"
                                        @click="onSertReward(reward.rewards[0].id, reward.user_id)">ส่งของรางวัล</li>
                                    <li class="dropdown-item p-2 hover:bg-gray-100 cursor-pointer"
                                        @click="onRecoverReward(reward.rewards[0].id, reward.user_id)">เปลี่ยนของรางวัล
                                    </li>
                                    <li class="dropdown-item p-2 hover:bg-gray-100 cursor-pointer"
                                        @click="onCancelReward(reward.id)">ยกเลิก</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="7" class="text-center p-3 text-red-600 font-bold">
                            ไม่มีข้อมูลการขอรางวัล
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-between mt-4">
                <button class="btn btn-sm btn-secondary" @click="goToPage(currentPage - 1)"
                    :disabled="currentPage <= 1">
                    Previous
                </button>
                <span>Page {{ currentPage }} of {{ totalPages }}</span>
                <button class="btn btn-sm btn-secondary" @click="goToPage(currentPage + 1)"
                    :disabled="currentPage >= totalPages">
                    Next
                </button>
            </div>
        </div>
    </div>
</template> -->
