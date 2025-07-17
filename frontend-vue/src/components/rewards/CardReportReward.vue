<template>
  <div class="m-auto">
    <!-- Reward Image -->
    <p class="flex justify-center items-center p-2 m-auto">
      <img
        v-if="props.reward?.reward_images?.[0]?.image_data"
        :src="`data:image/png;base64,${props.reward.reward_images[0].image_data}`"
        class="size-40 m-auto p-2 rounded-md shadow-md object-cover"
        alt="Reward Image"
      />
      <img
        v-else
        src="@/assets/images/keyboard.jpg"
        class="size-40 m-auto p-2 rounded-md shadow-md object-cover"
        alt="Default Image"
      />
    </p>

    <!-- Reward Name -->
    <p class="text-gray-800 font-semibold text-center mt-2">
      {{ props.reward.name }}
    </p>

    <!-- Reward Point -->
    <p class="text-sm text-gray-500 text-center">
      Point: {{ props.reward.point }}
    </p>

    <!-- Reward Amount & Add Button -->
    <div class="grid grid-cols-2 mt-2">
      <div class="flex justify-center items-center text-gray-700">
        Amount:
        <span
          :class="{
            'text-green-600 font-bold ml-1': props.reward.amount > 0,
            'text-red-500 font-bold ml-1': props.reward.amount <= 0,
          }"
        >
          {{ props.reward.amount }}
        </span>
      </div>

      <div class="flex justify-center items-center">
        <button
          @click="props.onAddCart(props.reward)"
          :disabled="props.reward.amount <= 0"
          class="px-4 py-1 rounded transition font-medium"
          :class="{
            'bg-blue-600 text-white hover:bg-blue-700': props.reward.amount > 0,
            'bg-gray-300 text-gray-500 cursor-not-allowed':
              props.reward.amount <= 0,
          }"
        >
          {{ props.reward.amount > 0 ? "Add" : "Out of stock" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  reward: {
    type: Object,
    default: () => ({}),
  },
  onAddCart: {
    type: Function,
    required: true,
  },
});
</script>
