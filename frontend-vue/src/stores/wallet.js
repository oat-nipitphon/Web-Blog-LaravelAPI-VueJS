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
      // for (const [key, value] of payload.entries()) {
      //   console.log(`store confirm selectd items ${key}:`, value);
      // }
      console.log('store confirm selectd items', payload);
      return;

      try {
        const response = await fetch(`/api/wellets`, {
          method: "POST",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: formData,
        });

        if (response.status !== 201 || response.status !== 200) {
          console.error("store confirm selectd items false ", response.error);
        }

        const data = await response.json();
        console.log("store confirm selectd items success ", data);
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
