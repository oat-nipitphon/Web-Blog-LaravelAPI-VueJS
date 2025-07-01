<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <div
      class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6"
    >
      <h1 class="text-2xl font-bold text-gray-800">Dashboard Manage Rewards</h1>
      <RouterLink
        :to="{ name: 'CreateRewardView' }"
        class="text-white font-bold block border border-blue-600 bg-blue-600 px-8 py-2 rounded-md hover:bg-blue-700 transition"
      >
        + New Reward
      </RouterLink>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">ผู้ใช้ทั้งหมด</p>
        <p class="text-2xl font-bold">250</p>
      </div>
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">ของรางวัล</p>
        <p class="text-2xl font-bold">30</p>
      </div>
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">แต้มที่แลก</p>
        <p class="text-2xl font-bold text-rose-600">5,230</p>
      </div>
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">แลกวันนี้</p>
        <p class="text-2xl font-bold text-blue-600">12</p>
      </div>
    </div>

    <!-- Chart + Recent Transactions -->
    <div class="grid md:grid-cols-2 gap-6 mb-10">
      <div class="bg-white p-4 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-2">สถิติการแลก (เดือนนี้)</h2>
        <ChartRewardsMonthly />
      </div>
      <div class="bg-white p-4 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-2">รายการล่าสุด</h2>
        <ul class="divide-y">
          <li v-for="n in 3" :key="n" class="py-2 text-sm flex justify-between">
            <span>ผู้ใช้ #{{ n }} แลกรางวัล</span>
            <span class="text-blue-600 font-semibold">+100 pts</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Rewards Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="text-center px-4 py-3 font-semibold">#</th>
            <th class="text-center px-4 py-3 font-semibold">Image</th>
            <th class="text-left px-4 py-3 font-semibold">Name</th>
            <th
              class="text-center px-4 py-3 font-semibold hidden md:table-cell"
            >
              Points
            </th>
            <th
              class="text-center px-4 py-3 font-semibold hidden md:table-cell"
            >
              Amount
            </th>
            <th class="text-center px-4 py-3 font-semibold">Status</th>
            <th class="text-center px-4 py-3 font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody v-if="rewards">
          <tr
            v-for="(reward, index) in rewards"
            :key="index"
            class="border-b hover:bg-gray-50 transition"
          >
            <td class="text-center px-4 py-3 font-medium">{{ index + 1 }}</td>
            <td class="text-center px-4 py-3">
              <img
                v-if="
                  reward.reward_images?.length &&
                  reward.reward_images[0].image_data
                "
                :src="
                  'data:image/png;base64,' + reward.reward_images[0].image_data
                "
                class="w-20 h-20 rounded-lg m-auto object-cover"
                alt="reward image"
              />
              <img
                v-else
                src="../../assets/images/keyboard.jpg"
                class="w-20 h-20 rounded-lg m-auto"
                alt="default image"
              />
            </td>
            <td class="px-4 py-3">{{ reward.name }}</td>
            <td class="text-center px-4 py-3 hidden md:table-cell">
              {{ reward.point }}
            </td>
            <td class="text-center px-4 py-3 hidden md:table-cell">
              {{ reward.amount }}
            </td>
            <td class="text-center px-4 py-3">
              {{ reward.reward_status.name }}
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
                          @click="onEditReward(reward.id)"
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
                          @click="onDeleteReward(reward.id)"
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-gray-700',
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
          <tr>
            <td
              colspan="7"
              class="text-center py-6 text-red-500 font-semibold text-lg"
            >
              No rewards available.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useRewardStore } from "@/stores/reward";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import ChartRewardsMonthly from "@/components/rewards/ChartRewardsMonthly.vue";

const router = useRouter();
const rewardStore = useRewardStore();
const { rewards } = storeToRefs(rewardStore);
const { storeGetRewards, storeDeleteReward } = useRewardStore();

const reportRewards = ref([]);

const isOpen = ref(false);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const selectOption = (option) => {
  isOpen.value = false;
};

const onEditReward = async (rewardID) => {
  isOpen.value = false;

  router.push({
    name: "EditRewardView",
    params: {
      id: rewardID,
    },
  });
};

const onDeleteReward = async (rewardID) => {
  isOpen.value = false;

  const success = await storeDeleteReward(rewardID);

  if (success) {
    rewards.value = rewards.value.filter((reward) => reward.id !== rewardID);
  }
};

onMounted(async () => {
  await rewardStore.storeGetRewards();
  console.log("manager report reward view", rewards);
});

const toggleStatus = async (rewardID, status) => {
  // const rewardID = reward.id;
  // const status = reward.status;
  try {
    const response = await fetch(
      `/api/admin/rewards/updateStatusReward/${rewardID}`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: JSON.stringify({
          reward_id: rewardID,
          status: status,
        }),
      }
    );

    const data = await response.json();

    if (response.status !== 200) {
      console.error("function update status reward false ", response.error);
    }

    console.log("function update status reward success ", data.reward.status);
  } catch (error) {
    console.error("function toggle status error", error);
  }
};
</script>
