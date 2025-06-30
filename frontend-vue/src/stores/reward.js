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
    async storeGetRewardStatus() {
      try {
        const response = await fetch(`/api/rewards/get_reward_status`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status !== 200) {
          console.error("store get reward status false ", response);
          return;
        }

        const data = await response.json();
        return data.rewardStatus;
      } catch (error) {
        console.error("store get reward status", error);
      }
    },

    async storeGetRewards() {
      try {
        const response = await fetch(`/api/rewards`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        if (!response.status == 200) {
          console.error("store get rewards false ", response.error);
          return;
        }

        const data = await response.json();
        this.rewards = data.rewards;
      } catch (error) {
        console.error("store get rewards function error ", error);
      }
    },

    async storeCreateReward(formData) {
      const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to create this reward?",
        icon: "question",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonColor: "#d33",
        showConfirmButton: true,
        confirmButtonText: "Save",
        confirmButtonColor: "#3085d6",
      });

      if (!result.isConfirmed) {
        return;
      }

      try {
        const response = await axiosAPI.post(`/api/rewards`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200, 201].includes(response.status)) {
          throw new Error("Failed to create reward");
          return;
        }

        const data = response.data;
        Swal.fire("Saved!", data.reward, "success");
        return true;
      } catch (error) {
        console.error("store create reward function error", error);
        return false;
      }
    },

    async storeGetReward(id) {
      try {
        const response = await fetch(`/api/rewards/${id}`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        if (!response.status == 200) {
          console.error("store get rewards false ", response.error);
          return;
        }

        const data = await response.json();
        return data.rewards;
      } catch (error) {
        console.error("store get rewards function error ", error);
      }
    },

    async storeUpdateReward(id, formData) {
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
        const response = await axiosAPI.post(`/api/rewards/${id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![201, 200].includes(response.status)) {
          throw new Error("store updated reward false ", response.message);
        }

        const data = response.data;
        Swal.fire("Updated!", data.reward, "success");
        return true;

      } catch (error) {
        console.error("store updated reward function error", error);
      }
    },

    async storeDeleteReward(id) {
      const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete this reward?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonColor: "#d33",
        showConfirmButton: true,
        confirmButtonText: "Delete",
        confirmButtonColor: "#3085d6",
      });

      if (!result.isConfirmed) {
        return false;
      }

      try {
        const response = await fetch(`/api/rewards/${id}`, {
          method: "DELETE",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status !== 200) {
          const error = await response.json();
          await Swal.fire(
            "Delete failed",
            error.message || "Unknown error",
            "error"
          );
          return false;
        }

        const data = await response.json();
        await Swal.fire({
          title: "Deleted!",
          text: data.status || "Reward has been deleted.",
          icon: "success",
          timer: 1300,
          timerProgressBar: true,
          showConfirmButton: false,
        });

        return true;
      } catch (error) {
        console.error("storeDeleteReward error:", error);
        await Swal.fire("Error", "Something went wrong.", "error");
        return false;
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
