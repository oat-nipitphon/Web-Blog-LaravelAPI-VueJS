import { defineStore } from "pinia";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axiosAPI from "@/services/axiosAPI";
import Swal from "sweetalert2";

export const useManagerBlogStore = defineStore("managerBlogStore", {
  state: () => ({
    users: [],
    profiles: [],
    errors: null,
  }),
  actions: {
    async storeManagerGetUserProfiles() {
      try {
        const response = await fetch(`/api/manager/user_profiles/get_reports`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200, 201].includes(response.status)) {
          console.error("store managet get false");
          return;
        }

        const data = await response.json();
        return data.userProfiles;
      } catch (error) {
        console.error(error);
      }
    },

    async storeManagerUpdateStatusAccount(formData) {
      try {
        const response = await axiosAPI.post(
          "/api/manager/user_profiles/status_account/update",
          formData,
          {
            "Content-Type": "application/json",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          }
        );

        if (![200].includes(response.status)) {
          console.error("store update status account false", response.error);
        }

        const data = await response.json();
        console.log("store update status account success", data.statusAccount);
        return true;
      } catch (error) {
        console.error(error);
      }
    },

    async storeManagerGetPosts() {
      try {
        const response = await fetch(`/api/manager/posts/get_reports`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200, 201].includes(response.status)) {
          console.error("store managet get false");
          return;
        }

        const data = await response.json();
        return data.posts;
      } catch (error) {
        console.error(error);
      }
    },

    async storeManagerGetRewards() {
      try {
        const response = await fetch(`/api/manager/rewards/get_reports`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200, 201].includes(response.status)) {
          console.error("store managet get false");
          return;
        }

        const data = await response.json();
        return data.rewards;
      } catch (error) {
        console.error(error);
      }
    },
  },
});
