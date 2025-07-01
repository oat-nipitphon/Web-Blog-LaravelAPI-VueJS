import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useRewardCartStore = defineStore("rewardCart", () => {
  const items = ref([]);

  // เพิ่มสินค้าเข้า cart
  const addItems = (reward) => {
    const existingItem = items.value.find((item) => item.id === reward.id);

    if (existingItem) {
      existingItem.amount += 1;
    } else {
      items.value.push({
        ...reward,
        amount: 1,
      });
    }
  };

  // ลบสินค้าออกจาก cart
  const removeItemAddCart = (reward) => {
    items.value = items.value.filter((item) => item.id !== reward.id);
  };

  // รีเซ็ตตะกร้าทั้งหมด
  const resetCart = () => {
    console.log('store reset cart item');
    items.value = [];
  };

  // จำนวนรางวัลทั้งหมดที่เลือก
  const countItems = computed(() => {
    return items.value.reduce((total, item) => total + item.amount, 0);
  });

  // แต้มรวมที่ใช้ไป
  const sumTotalPoint = computed(() => {
    return items.value.reduce(
      (total, item) => total + item.point * item.amount,
      0
    );
  });

  // แปลงรายการเป็น payload พร้อมส่ง
  const cartItems = computed(() =>
    items.value.map((item) => ({
      rewardID: item.id,
      rewardName: item.name,
      rewardPoint: item.point,
      rewardAmount: item.amount,
    }))
  );

  return {
    items,
    addItems,
    removeItemAddCart,
    resetCart,
    cartItems,
    countItems,
    sumTotalPoint,
  };
});
