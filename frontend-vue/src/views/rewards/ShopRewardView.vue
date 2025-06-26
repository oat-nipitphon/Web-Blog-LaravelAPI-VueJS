<template>
  <div class="bg-white rounded-2xl shadow-xl p-8 max-w-6xl mx-auto mt-10">
    <div class="grid gap-6 border-b border-gray-200 pb-6 mb-6">

      
      <div class="grid grid-cols-2 gap-4">

        <div class="flex justify-between items-center">
          <p>แต้มสมาชิก:</p>
          <p class="font-semibold text-right">
            {{ form.point }}
            <span class="text-sm font-normal text-gray-500">Point</span>
          </p>
        </div>

        <div class="flex justify-between items-center">
          <p>จำนวนรางวัล:</p>
          <!-- Show number sum items -->
           <h6 class="m-auto font-blod text-red-600">
            {{ counterItems }}
           </h6>
        </div>

      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="flex justify-between items-center">
          <p>จำนวนแต้ม:</p>
          <p class="text-rose-600 font-bold text-right">
            {{ totalPoint }}
            <span class="text-sm font-normal text-gray-500">Point</span>
          </p>
        </div>
        <div class="flex justify-between items-center">
          <p>แต้มคงเหลือ:</p>
          <p class="text-green-600 font-bold text-right">
            {{ checkPointSelectd }}
            <span class="text-sm font-normal text-gray-500">Point</span>
          </p>
        </div>
      </div>

    </div>

    <!-- Button Confirm and Reset Event Selectd Items -->
    <div class="grid grid-cols-2 items-end gap-4 border-b border-gray-200 pb-6 mb-6">
      <div class="col-span-2 flex justify-end gap-4">
        <button
          type="button"
          :disabled="checkPointSelectd < 0 || cartItemCounters === 0"
          @click="onSave"
          class="px-6 py-2 rounded-xl shadow transition text-white"
          :class="{
            'bg-gray-400 cursor-not-allowed':
              checkPointSelectd < 0 || cartItemCounters === 0,
            'bg-blue-500 hover:bg-blue-700':
              checkPointSelectd >= 0 && cartItemCounters > 0,
          }"
        >
          แลกของรางวัล
        </button>

        <button
          type="button"
          :disabled="cartItemCounters === 0"
          @click="onResetItemsCart"
          class="px-6 py-2 rounded-xl shadow transition text-white"
          :class="{
            'bg-gray-400 cursor-not-allowed': cartItemCounters === 0,
            'bg-red-600 hover:bg-red-700': cartItemCounters > 0,
          }"
        >
          รีเซ็ตแลกของรางวัล
        </button>
      </div>
    </div>

  </div>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { RouterLink } from "vue-router";
import { storeToRefs } from "pinia";

import { useAuthStore } from "@/stores/auth";
import { useWalletStore } from '@/stores/wallet'
import { useRewardStore } from "@/stores/reward";
import { useRewardCartStore } from "@/stores/reward-card";

const authStore = useAuthStore();
const rewardStore = useRewardStore();
const rewardCartStore = useRewardCartStore();

const { users } = storeToRefs(authStore);
const { rewards } = storeToRefs(rewardStore);
const { counterItems, cartItemCounters, totalPoint } =
  storeToRefs(rewardCartStore);

const { storeConfirmSelectdItems } = useWalletStore();
const { storeGetRewards } = useRewardStore();

onMounted(async () => {
  await rewardStore.storeGetRewards();
});

const form = reactive({
  walletID: authStore?.users?.wallet?.id || "",
  point: authStore?.users?.wallet?.point || 0,
  items: "",
});

const checkPointSelectd = computed(() => {
  return form.point - totalPoint.value;
})

const onSave = async () => {
  const formData = new FormData();
  formData.append("wallet_id", form.walletID);
  formData.append("point", checkPointSelectd.value);
  formData.append("items", JSON.stringify(counterItems.value));

  for (const [key, value] of formData.entries()) {
    console.log(`${key}:`, value);
  }

  return;

  await storeConfirmSelectdItems(formData);

};

const onResetItemsCart = () => {
  rewardCartStore.resetCart();
};
</script>
