<template>
  <div
    class="bg-white rounded-2xl shadow-xl p-8 max-w-6xl mx-auto mt-10 space-y-6"
  >
    <!-- แต้มรวมและจำนวนรางวัล -->
    <div class="grid md:grid-cols-2 gap-6 border-b border-gray-200 pb-6">
      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">แต้มสมาชิก:</p>
        <p class="font-bold text-blue-700 text-lg">
          {{ form.point }}
          <span class="text-sm font-normal text-gray-500">Point</span>
        </p>
      </div>

      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">จำนวนรางวัล:</p>
        <p class="font-bold text-red-600 text-lg">{{ countItems }}</p>
      </div>
    </div>

    <!-- แต้มที่ใช้ และแต้มคงเหลือ -->
    <div class="grid md:grid-cols-2 gap-6 border-b border-gray-200 pb-6">
      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">จำนวนแต้มที่ใช้:</p>
        <p class="font-bold text-rose-600 text-lg">
          {{ sumTotalPoint }}
          <span class="text-sm font-normal text-gray-500">Point</span>
        </p>
      </div>

      <div class="flex justify-between items-center">
        <p class="text-gray-700 text-lg">แต้มคงเหลือ:</p>
        <p class="font-bold text-green-600 text-lg">
          {{ remainingPoint }}
          <span class="text-sm font-normal text-gray-500">Point</span>
        </p>
      </div>
    </div>

    <!-- ปุ่มแลกและรีเซ็ต -->
    <div class="flex flex-col md:flex-row justify-end gap-4">
      <button
        type="button"
        :disabled="sumTotalPoint <= 0 || countItems === 0"
        @click="onConfirmExchange"
        class="w-full md:w-auto px-6 py-2 rounded-xl text-white font-semibold transition shadow-md"
        :class="{
          'bg-gray-400 cursor-not-allowed':
            sumTotalPoint <= 0 || countItems === 0,
          'bg-blue-600 hover:bg-blue-700': sumTotalPoint > 0 && countItems > 0,
        }"
      >
        แลกของรางวัล
      </button>

      <button
        type="button"
        :disabled="countItems === 0"
        @click="resetCart"
        class="w-full md:w-auto px-6 py-2 rounded-xl text-white font-semibold transition shadow-md"
        :class="{
          'bg-gray-400 cursor-not-allowed': countItems === 0,
          'bg-red-600 hover:bg-red-700': countItems > 0,
        }"
      >
        รีเซ็ตแลกของรางวัล
      </button>
    </div>

    <!-- แสดงรายการของรางวัลที่เลือก -->
    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">รายการของรางวัล</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div
          v-for="(reward, index) in rewards"
          :key="index"
          class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm"
        >
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
import { ref, reactive, computed, onMounted } from "vue";
import { storeToRefs } from "pinia";

import { useAuthStore } from "@/stores/auth";
import { useRewardStore } from "@/stores/reward";
import { useRewardCartStore } from "@/stores/reward-cart";

const authStore = useAuthStore();
const rewardStore = useRewardStore();
const rewardCartStore = useRewardCartStore();

const { users } = storeToRefs(authStore);
const { rewards } = storeToRefs(rewardStore);
const { storeGetRewards } = rewardStore;
const { addItems, cartItems, countItems, sumTotalPoint, resetCart } =
  storeToRefs(rewardCartStore);

const form = reactive({
  walletID: users.value?.wallet?.id || "",
  point: users.value?.wallet?.point || 0,
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

const onConfirmExchange = async () => {
  const payload = {
    wallet_id: form.walletID,
    point: remainingPoint.value,
    items: cartItems.value,
  };

  console.log("📦 ส่งข้อมูลแลกของรางวัล:", payload);

  // TODO: เรียก API ส่ง payload
  // await rewardCartStore.storeConfirmSelectdItems(payload);
};
</script>
