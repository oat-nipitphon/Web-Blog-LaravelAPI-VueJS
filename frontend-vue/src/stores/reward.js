import { defineStore } from "pinia";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";

export const useRewardStore = defineStore("rewardStore", {
  state: () => ({
    rewards: [],
    errors: [],
  }),
  actions: {

    async storeGetRewards() {
      try {
        const response = await fetch(`/api/rewards`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        if (!response.ok) {
          console.error("store get rewards false ", response.error);
          return;
        }

        const data = await response.json();
        this.rewards = data.rewards;
        
        console.log("store get rewards success", this.rewards);
      } catch (error) {
        console.error("store get rewards function error ", error);
      }
    },

    async storeCreateReward(formData) {
      const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to created this reward?",
        icon: "question",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonColor: "#d33",
        showConfirmButton: true,
        confirmButtonText: "Save",
        confirmButtonColor: "#3085d6",
      });

      if (result.isConfirmed) {
        Swal.close();
        return;
      }

      try {
        const response = await axiosAPI.post(`/api/rewards`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status !== 200) {
          throw new Error("store create reward false ", response.message);
        }

        const data = await response.json();
        console.log("store create reward success ", data.reward);
        Swal.fire("Saved!", "Your reward has been saved.", "success");
        this.router.push({
          name: 'ManagerReportRewardsView'
        });

      } catch (error) {
        console.error("store create reward function error", error);
      }
    },

    async storeEditReward(formData) {
      const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to updated this reward?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonColor: "#d33",
        showConfirmButton: true,
        confirmButtonText: "Update",
        confirmButtonColor: "#3085d6",
      });

      if (result.isConfirmed) {
        Swal.close();
        return;
      }

      try {
        formData.append("_method", "PUT");
        const response = await axiosAPI.post(`/api/rewards`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status !== 200) {
          throw new Error("store updated reward false ", response.message);
        }

        const data = await response.json();
        console.log("store updated reward success ", data.reward);
        Swal.fire("Saved!", "Your reward has been saved.", "success");

      } catch (error) {
        console.error("store updated reward function error", error);
      }
    },

    async storeDeleteReward(id) {
      try {
        const response = await fetch(`/api/reward/${id}`, {
          method: "DELETE",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response !== 200) {
          console.error(
            "store delete reward false ",
            response.error || response.message
          );
        }

        console.log('store delete reward success ');
        // const data = await response.json();
        // return data.modelReward.filter((reward) => reward.id !== id);
        
      } catch (error) {
        console.error("store delete reward function error ", error);
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
