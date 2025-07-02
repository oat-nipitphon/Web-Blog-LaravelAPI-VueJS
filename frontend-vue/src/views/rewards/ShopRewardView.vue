<template>
  <div
    class="bg-white rounded-2xl shadow-xl p-8 max-w-6xl mx-auto mt-10 space-y-6"
  >
    <!-- Wallet Point -->
    <div class="grid md:grid-cols-2 gap-6 border-b border-gray-200 pb-6">
      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">Wallet Point:</p>
        <p class="font-bold text-blue-700 text-lg">
          {{ form.point }}
          {{ remainingPoint }}
          <span class="text-sm font-normal text-gray-500">Point</span>
        </p>
      </div>

      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">Items:</p>
        <p class="font-bold text-red-600 text-lg">{{ countItems }}</p>
      </div>
    </div>

    <!-- Sum Total Point Use And Pemaining Point -->
    <div class="grid md:grid-cols-2 gap-6 border-b border-gray-200 pb-6">
      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">Use Point:</p>
        <p class="font-bold text-rose-600 text-lg">
          {{ sumTotalPoint }}
          <span class="text-sm font-normal text-gray-500">Point</span>
        </p>
      </div>

      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">PemainigPoint:</p>
        <p class="font-bold text-green-600 text-lg">
          {{ remainingPoint }}
          <span class="text-sm font-normal text-gray-500">Point</span>
        </p>
      </div>
    </div>

    <!-- Button Confirmd Items Selectd -->
    <div class="flex flex-col md:flex-row justify-end gap-4">
      <button
        type="button"
        :disabled="sumTotalPoint <= 0 || countItems === 0"
        @click="onConfirmedSelectdItems"
        class="w-full md:w-auto px-6 py-2 rounded-xl text-white font-semibold transition shadow-md"
        :class="{
          'bg-gray-400 cursor-not-allowed':
            sumTotalPoint <= 0 || countItems === 0,
          'bg-blue-600 hover:bg-blue-700': sumTotalPoint > 0 && countItems > 0,
        }"
      >
        Confirm Selectd Items
      </button>

      <button
        type="button"
        :disabled="countItems === 0"
        @click="onResetCartItem"
        class="w-full md:w-auto px-6 py-2 rounded-xl text-white font-semibold transition shadow-md"
        :class="{
          'bg-gray-400 cursor-not-allowed': countItems === 0,
          'bg-red-600 hover:bg-red-700': countItems > 0,
        }"
      >
        Reset Cart Item
      </button>
    </div>

    <!-- Report Reward -->
    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">Rewards</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div
          v-for="(reward, index) in rewards"
          :key="index"
          class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm"
        >
          <p class="flex justify-center items-center p-2 m-auto">
            <img
              v-if="reward?.reward_images[0]?.image_data !== null"
              :src="
                'data:image/png;base64,' + reward?.reward_images[0]?.image_data
              "
              class="size-40 m-auto p-2 rounded-md"
              alt=""
            />
            <img
              v-else
              src="../../assets/images/keyboard.jpg"
              class="size-40 m-auto p-2 rounded-md"
              alt=""
            />
          </p>
          <p class="text-gray-800 font-semibold text-center">
            {{ reward.name }}
          </p>
          <p class="text-sm text-gray-500 text-center">
            Point: {{ reward.point }}
          </p>
          <div class="grid grid-cols-2">
            <div class="flex justify-center m-2 p-2">
              Amount: {{ reward.amount }}
            </div>
            <div class="flex justify-center m-2 p-2">
              <button
                @click="addToCart(reward)"
                class="text-blue-600 hover:underline"
              >
                Add
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Swal from "sweetalert2";
import { ref, reactive, computed, onMounted } from "vue";
import { storeToRefs } from "pinia";

import { useAuthStore } from "@/stores/auth";
import { useRewardStore } from "@/stores/reward";
import { useRewardCartStore } from "@/stores/reward-cart";
import { useWalletStore } from "@/stores/wallet";

const authStore = useAuthStore();
const rewardStore = useRewardStore();
const rewardCartStore = useRewardCartStore();

const { users } = storeToRefs(authStore);
const { rewards } = storeToRefs(rewardStore);
const { storeGetRewards } = rewardStore;
const { cartItems, countItems, sumTotalPoint } = storeToRefs(rewardCartStore);

const { storeConfirmSelectdItems } = useWalletStore();

const form = reactive({
  walletID: users.value?.wallet?.id,
  point: users.value?.wallet?.point,
  items: [],
});

const remainingPoint = computed(() => {
  return form.point - sumTotalPoint.value;
});

onMounted(async () => {
  await storeGetRewards();
});

const addToCart = (reward) => {
  rewardCartStore.addItems(reward);
};

const onResetCartItem = () => {
  rewardCartStore.resetCart();
};

const onConfirmedSelectdItems = async () => {
  const payload = {
    wallet_id: form.walletID,
    point: remainingPoint.value,
    items: cartItems.value,
  };

  // const formData = new FormData();
  // formData.append("wallet_id", form.walletID);
  // formData.append("point", remainingPoint.value);
  // formData.append("items", JSON.stringify(cartItems.value));

  const success = await storeConfirmSelectdItems(payload);

  if (success) {
    console.log(success);
    Swal.fire({
      title: "Confirmed success",
      text: "confirmed selectd items cart successfully.",
      icon: "success",
      timer: 1200,
    }).then(() => {
      Swal.close();
      window.location.reload();
      return;
    });
  }
};
</script>
