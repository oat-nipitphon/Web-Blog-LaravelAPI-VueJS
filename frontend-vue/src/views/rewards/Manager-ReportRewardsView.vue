<template>
  <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div
      class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6"
    >
      <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">
        Manage Rewards
      </h1>
      <RouterLink
        :to="{ name: 'CreateRewardView' }"
        class="block border border-blue-600 bg-blue-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
      >
        New
      </RouterLink>
    </div>

    <table class="w-full text-sm text-gray-700">
      <thead>
        <tr class="bg-gray-100 text-xs uppercase tracking-wider">
          <th class="text-center px-4 py-3 font-semibold">#</th>
          <th class="text-center px-4 py-3 font-semibold">Image</th>
          <th class="text-left px-4 py-3 font-semibold">Name</th>
          <th class="text-center px-4 py-3 font-semibold hidden md:table-cell">
            Points
          </th>
          <th class="text-center px-4 py-3 font-semibold hidden md:table-cell">
            Amount
          </th>
          <th class="text-center px-4 py-3 font-semibold">Status</th>
          <th class="text-center px-4 py-3 font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody v-if="rewards">
        <tr

          class="border-b hover:bg-gray-50 transition"
        >
          <td class="text-center px-4 py-3 font-medium">
            <!-- Counters -->
          </td>

          <td class="text-center px-4 py-3">
            <!-- Image -->
          </td>

          <td class="px-4 py-3">
            <!-- Name -->
          </td>

          <td class="text-center px-4 py-3 hidden md:table-cell">
           <!-- Point -->
          </td>

          <td class="text-center px-4 py-3 hidden md:table-cell">
           <!-- Amount -->
          </td>

          <td class="text-center px-4 py-3">

            <!-- Status -->

            <!-- <button
              @click="toggleStatus(reward)"
              class="text-sm font-semibold rounded-full px-4 py-1 transition"
              :class="
                reward.status === 'true'
                  ? 'bg-green-100 text-green-700 hover:bg-green-200'
                  : 'bg-red-100 text-red-700 hover:bg-red-200'
              "
            >
              {{ reward.status === "true" ? "Active" : "Inactive" }}
            </button> -->
          </td>

          <td class="">
            <!-- Modal Event Show and Edit ?? -->
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
</template>
<script setup>
import { ref, onMounted, reactive } from "vue";
import { RouterLink } from "vue-router";
import { storeToRefs } from "pinia";
import { useRewardStore } from '@/stores/reward'

const rewardStore = useRewardStore();
const { rewards } = storeToRefs(rewardStore);
const { storeGetRewards } = useRewardStore();

const reportRewards = ref([]);
const rewardUpdate = ref([]);

onMounted(async () => {
  reportRewards.value = await rewards.storeAdminAPIGetRewards();
  console.log('manager report rewards view ', reportRewards.value);
});


// const toggleStatus = async (reward) => {
//   const rewardID = reward.id;
//   const status = reward.status;
//   try {
//     const res = await fetch(
//       `/api/admin/rewards/updateStatusReward/${rewardID}`,
//       {
//         method: "POST",
//         headers: {
//           "Content-Type": "application/json",
//           authorization: `Bearer ${localStorage.getItem("token")}`,
//         },
//         body: JSON.stringify({
//           rewardID: rewardID,
//           status: status,
//         }),
//       }
//     );

//     const data = await res.json();

//     if (res.ok) {
//       window.location.reload();
//     } else {
//       console.log("update false", res);
//     }
//   } catch (error) {
//     console.error("function toggle status error", error);
//   }
// };

</script>
