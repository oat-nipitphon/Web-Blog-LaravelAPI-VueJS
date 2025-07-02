import { defineStore } from "pinia";
import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";

export const useWalletStore = defineStore("walletStore", {
  state: () => ({
    wallets: [],
    errors: [],
  }),
  actions: {
    // Confirmd Selectd Items
    async storeConfirmSelectdItems(payload) {
      // for (const [key, value] of formData.entries()) {
      //   console.log(`${key}:`, value);
      // }

      // return;

      const result = await Swal.fire({
        title: "Confirmed ?",
        text: "Do you want to confirmed selectd items ?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonColor: "#d33",
        showConfirmButton: true,
        confirmButtonText: "Confirmed",
        confirmButtonColor: "#3085d6",
      });

      if (!result.isConfirmed) {
        Swal.close();
        return;
      }

      try {
        const response = await fetch("/api/wellets/counters", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (!response.ok) {
          console.error("Failed:", data.message);
          return false;
        }

        console.log("Success:", data);
        return true;
      } catch (error) {
        console.error("store confirm selectd items function error ", error);
      }
    },

    // Get Card Profile Report Reward Selectd Items
    async getReportRewards(userID) {
      try {
        const res = await fetch(`/api/cartItems/getReportReward/${userID}`, {
          method: "GET",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        const data = await res.json();

        if (!res.ok) {
          console.log("store get report reward request false", res);
        } else {
          return data.userPointCounter;
        }
      } catch (error) {
        console.error("store get report reward function error", error);
      }
    },

    // Card Profile Report Reward Selectd Event Delete Item
    async cancelReward(itemID) {
      try {
        const res = await axiosAPI.post(
          `/api/cartItems/cancel_reward/${itemID}`,
          {
            headers: {
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        console.log("cancel api reward", res);
      } catch (error) {
        console.error(error);
      }
    },
  },
});
