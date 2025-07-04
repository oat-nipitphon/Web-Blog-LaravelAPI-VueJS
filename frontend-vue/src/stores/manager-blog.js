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

    async storeManagerGetUserProfiles () {
      try {
        const response = await fetch(`/api/manager/user_profiles/get_reports`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });

        if (![200, 201].includes(response.status)) {
          console.error('store managet get false');
          return;
        }

        const data = await response.json();
        console.log('store manager get success', data.userProfiles);
        return data.userProfiles;

      } catch (error) {
        console.error(error);
      }
    },
    async storeManagerGetPosts () {
      try {
        const response = await fetch(`/api/manager/posts/get_reports`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });

        if (![200, 201].includes(response.status)) {
          console.error('store managet get false');
          return;
        }

        console.log('store manager get success', response.data);
        const data = response.data;
        return data;

      } catch (error) {
        console.error(error);
      }
    },
    async storeManagerGetReward () {
      try {
        const response = await fetch(`/api/manager/rewards/get_reports`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });

        if (![200, 201].includes(response.status)) {
          console.error('store managet get false');
          return;
        }

        console.log('store manager get success', response.data);
        const data = response.data;
        return data;

      } catch (error) {
        console.error(error);
      }
    },



  },
});
