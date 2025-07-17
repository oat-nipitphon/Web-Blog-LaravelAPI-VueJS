import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useRewardCartStore = defineStore("rewardCart", () => {
  const items = ref([]); // 🛒 ตะกร้าสินค้า

  // ✅ เพิ่มสินค้าเข้า cart
  const addToCart = (reward) => {
    // เช็คว่ามี stock เหลือไหม
    if (reward.amount <= 0) {
      console.warn(`Reward "${reward.name}" is out of stock.`);
      return false; // ❌ หยุดถ้า stock หมด
    }

    const existingItem = items.value.find((item) => item.id === reward.id);

    if (existingItem) {
      existingItem.amount += 1; // เพิ่มจำนวนใน cart
    } else {
      items.value.push({
        ...reward,
        amount: 1,
      });
    }

    return true; // ✅ เพิ่มสำเร็จ
  };

  // ✅ ลบสินค้าออกจาก cart (1 ชิ้น)
  const removeOneFromCart = (reward) => {
    const existingItem = items.value.find((item) => item.id === reward.id);
    if (existingItem) {
      if (existingItem.amount > 1) {
        existingItem.amount -= 1;
      } else {
        items.value = items.value.filter((item) => item.id !== reward.id);
      }
    }
  };

  // ✅ ลบสินค้าทั้งหมดของรายการนั้นออกจาก cart
  const removeAllFromCart = (reward) => {
    items.value = items.value.filter((item) => item.id !== reward.id);
  };

  // ✅ รีเซ็ตตะกร้าทั้งหมด
  const resetCart = () => {
    console.log("store resetCart: clear all cart items");
    items.value = [];
  };

  // ✅ จำนวนรวมรางวัลที่เลือก (ทุก amount)
  const countItems = computed(() => {
    return items.value.reduce((total, item) => total + item.amount, 0);
  });

  // ✅ รวมแต้มที่ใช้ใน cart
  const sumTotalPoint = computed(() => {
    return items.value.reduce(
      (total, item) => total + item.point * item.amount,
      0
    );
  });

  // ✅ แปลง cart เป็น payload พร้อมส่ง API
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
    addToCart, // ✅ ใช้ชื่อเดียวกับใน View
    removeOneFromCart, // ✅ ลบทีละ 1
    removeAllFromCart, // ✅ ลบทั้งก้อน
    resetCart, // ✅ รีเซ็ตทั้งหมด
    cartItems, // ✅ payload สำหรับ API
    countItems, // ✅ จำนวนรวม
    sumTotalPoint, // ✅ แต้มรวม
  };
});
