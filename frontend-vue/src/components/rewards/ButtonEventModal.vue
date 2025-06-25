<template>
  <div class="inline-flex space-x-1">
    <!-- Show Button -->
    <button
      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs"
      @click="isShow = true"
    >
      Show
    </button>

    <!-- Edit Button -->
    <button
      class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs"
      @click="openEditModal(reward)"
    >
      Edit
    </button>

    <!-- Delete Button -->
    <button
      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs"
      @click="onDelete(reward.id)"
    >
      Delete
    </button>
  </div>

  <!-- Modal Edit Reward -->
  <TransitionRoot appear :show="isEditModalOpen" as="template">
    <Dialog as="div" class="relative z-50" @close="isEditModalOpen = false">
      <div class="fixed inset-0 bg-black bg-opacity-30" aria-hidden="true" />
      <div class="fixed inset-0 flex items-center justify-center p-4">
        <DialogPanel
          class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6"
        >
          <DialogTitle class="text-lg font-medium text-gray-900 mb-4">
            แก้ไข Reward ID: {{ rewardUpdate.id }}
          </DialogTitle>

          <!-- Form Fields -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Name</label
              >
              <input
                type="text"
                v-model="formUpdate.name"
                class="input-style"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Point</label
              >
              <input
                type="text"
                v-model="formUpdate.point"
                class="input-style"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Amount</label
              >
              <input
                type="text"
                v-model="formUpdate.amount"
                class="input-style"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Status</label
              >
              <select v-model="formUpdate.status" class="input-style">
                <option value="true">true</option>
                <option value="false">false</option>
              </select>
            </div>
          </div>

          <!-- Footer -->
          <div class="mt-6 flex justify-end space-x-3">
            <button
              @click="onUpdateReward"
              class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow"
            >
              Update
            </button>
            <button
              @click="isEditModalOpen = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm px-4 py-2 rounded-lg shadow"
            >
              Cancel
            </button>
          </div>
        </DialogPanel>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionRoot,
} from "@headlessui/vue";
import { ref, reactive } from "vue";

const isShow = ref(false);
const isEditModalOpen = ref(false);
const reward = ref({});
const rewardUpdate = ref({});
const formUpdate = reactive({
  name: "",
  point: "",
  amount: "",
  status: "true",
});

function openEditModal(data) {
  rewardUpdate.value = { ...data };
  Object.assign(formUpdate, data);
  isEditModalOpen.value = true;
}

function onUpdateReward() {
  console.log("Updating...", formUpdate);
  // TODO: ส่ง formUpdate ไปยัง backend
  isEditModalOpen.value = false;
}

function onDelete(id) {
  console.log("Delete ID:", id);
}
</script>

<!-- <style scoped>
.input-style {
  @apply mt-1 block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500;
}
</style> -->
