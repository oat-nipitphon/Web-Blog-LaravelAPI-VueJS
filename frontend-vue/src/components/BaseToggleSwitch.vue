<template>
  <div class="flex items-center justify-between p-4 bg-white rounded shadow">
    <!-- User Info -->
    <!-- <div>
      <p class="font-medium">User ID: {{ userID }}</p>
      <p>
        Status:
        <span :class="isActive ? 'text-green-600' : 'text-red-600'">
          {{ isActive ? 'Active' : 'Inactive' }}
        </span>
      </p>
    </div> -->

    <!-- Toggle Switch -->
    <label class="flex items-center cursor-pointer">
      <div class="relative">
        <input
          type="checkbox"
          :checked="isActive"
          :disabled="loading"
          @change="toggleStatus"
          class="sr-only peer"
        />
        <div
          class="w-14 h-8 rounded-full transition-colors duration-300 ease-in-out"
          :class="[
            loading ? 'bg-gray-400' : isActive ? 'bg-green-500' : 'bg-gray-300',
          ]"
        ></div>
        <div
          class="absolute left-1 top-1 w-6 h-6 rounded-full bg-white shadow transition-transform duration-300 ease-in-out"
          :class="isActive ? 'translate-x-6' : ''"
        ></div>
        <div
          v-if="loading"
          class="absolute inset-0 flex items-center justify-center"
        >
          <svg
            class="animate-spin h-5 w-5 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            ></path>
          </svg>
        </div>
      </div>
    </label>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
  userID: {
    type: Number,
    required: true,
  },
  modelValue: {
    type: [Boolean, String],
    required: true,
  },
});
const emit = defineEmits(["update:modelValue", "status-changed"]);

const loading = ref(false);

// Convert "active"/"inactive" to Boolean
const isActive = computed(() =>
  typeof props.modelValue === "string"
    ? props.modelValue.toLowerCase() === "active"
    : !!props.modelValue
);

const toggleStatus = async () => {
  if (loading.value) return;
  loading.value = true;

  const newStatus = !isActive.value;
  emit("update:modelValue", newStatus ? "active" : "inactive");
  await emit("status-changed", props.userID, newStatus);

  loading.value = false;
};
</script>
