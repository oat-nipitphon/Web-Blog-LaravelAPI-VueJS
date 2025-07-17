<template>
  <div
    class="bg-white rounded-2xl shadow-xl p-8 max-w-6xl mx-auto mt-10 space-y-6"
  >
    <!-- Wallet Summary -->
    <div class="grid grid-cols-3">
      <div class="flex justify-center items-center">
        <p class="text-gray-700 text-lg">Wallet Point:</p>
        <p class="font-bold text-blue-700 text-lg ml-5">
          {{ remainingPoint }}
        </p>
      </div>
      <div class="flex justify-center items-center">
        <p class="text-gray-700 text-lg">Items:</p>
        <p class="font-bold text-red-600 text-lg ml-5">{{ countItems }}</p>
      </div>
      <div class="flex justify-center items-center">
        <p class="text-gray-700 text-lg">Use Point:</p>
        <p class="font-bold text-rose-600 text-lg ml-5">
          {{ sumTotalPoint }}
        </p>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-2 gap-4">
      <button
        type="button"
        :disabled="sumTotalPoint <= 0 || countItems === 0"
        @click="onConfirmedSelectdItems"
        class="w-full text-white font-bold py-2 px-4 rounded transition"
        :class="{
          'bg-gray-400 cursor-not-allowed':
            sumTotalPoint <= 0 || countItems === 0,
          'bg-blue-600 hover:bg-blue-700': sumTotalPoint > 0 && countItems > 0,
        }"
      >
        Confirm
      </button>

      <button
        type="button"
        :disabled="countItems === 0"
        @click="onResetCartItem"
        class="w-full text-white font-bold py-2 px-4 rounded transition"
        :class="{
          'bg-gray-400 cursor-not-allowed': countItems === 0,
          'bg-red-600 hover:bg-red-700': countItems > 0,
        }"
      >
        Reset
      </button>
    </div>

    <!-- Reward Items -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-6">
      <div
        v-for="(reward, index) in rewards"
        :key="index"
        class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm"
      >
        <CardReportReward :reward="reward" :onAddCart="addToCart" />
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

import CardReportReward from "@/components/rewards/CardReportReward.vue";

const authStore = useAuthStore();
const rewardStore = useRewardStore();
const rewardCartStore = useRewardCartStore();
const walletStore = useWalletStore();

const { users } = storeToRefs(authStore);
const { rewards } = storeToRefs(rewardStore);
const { storeGetRewards } = rewardStore;
const { cartItems, countItems, sumTotalPoint } = storeToRefs(rewardCartStore);

const { storeConfirmSelectdItems } = walletStore;

const form = reactive({
  walletID: users.value?.wallet?.id,
  point: users.value?.wallet?.point,
  items: [],
});

const remainingPoint = computed(() => {
  return form.point - sumTotalPoint.value;
});

// 🟢 โหลด rewards เมื่อ mount
onMounted(async () => {
  await storeGetRewards();
});

// 🟢 Add ของไป Cart และลดจำนวนคงเหลือ
const addToCart = (reward) => {
  if (reward.amount <= 0) {
    Swal.fire("สินค้าหมด", "ไม่สามารถเพิ่มลงตะกร้าได้", "warning");
    return;
  }

  rewardCartStore.addToCart(reward);

  const target = rewards.value.find((r) => r.id === reward.id);
  if (target && target.amount > 0) {
    target.amount -= 1;
  }
};

// 🟢 Reset Cart และคืนจำนวนคงเหลือ
const onResetCartItem = () => {
  cartItems.value.forEach((item) => {
    const target = rewards.value.find((r) => r.id === item.id);
    if (target) {
      target.amount += item.qty; // คืนของ
    }
  });
  rewardCartStore.resetCart();
};

// 🟢 ยืนยันรายการใน Cart
const onConfirmedSelectdItems = async () => {
  const payload = {
    wallet_id: form.walletID,
    point: remainingPoint.value,
    items: cartItems.value,
  };

  const result = await Swal.fire({
    title: "Confirm Redeem?",
    text: "ยืนยันการแลกของรางวัลหรือไม่?",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, confirm it!",
    cancelButtonText: "Cancel",
  });

  if (!result.isConfirmed) return;

  const success = await storeConfirmSelectdItems(payload);

  if (success) {
    Swal.fire({
      title: "Success",
      text: "ยืนยันการแลกของรางวัลสำเร็จ!",
      icon: "success",
      timer: 1200,
    }).then(() => {
      rewardCartStore.resetCart();
      storeGetRewards(); // Refresh จำนวน reward ใหม่
    });
  } else {
    Swal.fire("Error", "เกิดข้อผิดพลาดในการแลกของรางวัล", "error");
  }
};
</script>
