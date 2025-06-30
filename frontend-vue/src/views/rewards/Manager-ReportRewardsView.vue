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
        class="text-white font-bold block border border-blue-600 bg-blue-600 px-8 py-2 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
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
          v-for="(reward, index) in rewards"
          :key="index"
          class="border-b hover:bg-gray-50 transition"
        >
          <td class="text-center px-4 py-3 font-medium">
            {{ index }}
          </td>

          <td
            class="text-center px-4 py-3"
            v-for="image in reward.reward_images"
            :key="image.id"
          >
            <img
              v-if="image.image_data"
              :src="'data:image/png;base64,' + image?.image_data"
              class="w-20 h-20 rounded-lg m-auto"
              alt=""
            />
            <img
              v-else
              src="../../assets/images/keyboard.jpg"
              class="w-20 h-20 rounded-lg m-auto"
              alt=""
            />
          </td>

          <td class="px-4 py-3">
            {{ reward.name }}
          </td>

          <td class="text-center px-4 py-3 hidden md:table-cell">
            {{ reward.point }}
          </td>

          <td class="text-center px-4 py-3 hidden md:table-cell">
            {{ reward.amount }}
          </td>

          <td class="text-center px-4 py-3">
            {{ reward.reward_status.name }}
          </td>

          <td class="flex justify-items-center">
            <!-- <div
              class="relative inline-block text-left"
              @click.outside="isOpen = false"
            >
              <button
                @click="toggleDropdown"
                class="inline-flex justify-center w-full rounded-md bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 transition"
              >
                Options
                <svg
                  class="-mr-1 ml-2 h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>

              <div
                v-if="isOpen"
                class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50"
              >
                <div class="py-1">
                  <button
                    @click="toggleStatus(reward.reward_status)"
                    class="text-sm font-semibold rounded-full px-4 py-1 transition"
                    :class="
                      reward.reward_status === 'active'
                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                        : 'bg-red-100 text-red-700 hover:bg-red-200'
                    "
                  >
                    {{
                      reward.reward_status === "active"
                        ? "active"
                        : "comming_soon"
                        ? "comming_soon"
                        : "dissable"
                    }}
                  </button>
                </div>
                <div class="py-1">
                  <a
                    href="#"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    @click='onEditReward(reward.id)'
                  >
                    Edit
                  </a>
                  <a
                    href="#"
                    class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100"
                    @click="onDeleteReward(reward.id)"
                  >
                    Delete
                  </a>
                </div>
              </div>

            </div> -->
            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50"
                >
                  Options
                  <ChevronDownIcon
                    class="-mr-1 size-5 text-gray-400"
                    aria-hidden="true"
                  />
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
                  class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-hidden"
                >
                  <div class="py-1">
                    <MenuItem v-slot="{ active }">
                        <button
                        @click="onEditReward(reward.id)"
                          type="submit"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900 outline-hidden'
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
                          type="submit"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900 outline-hidden'
                              : 'text-gray-700',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Delete
                        </button>
                    </MenuItem>
                    <!-- <form method="POST" action="#">
                      <MenuItem v-slot="{ active }">
                        <button
                          type="submit"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900 outline-hidden'
                              : 'text-gray-700',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Sign out
                        </button>
                      </MenuItem>
                    </form> -->
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
</template>
<script setup>
import { ref, onMounted } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useRewardStore } from "@/stores/reward";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

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
