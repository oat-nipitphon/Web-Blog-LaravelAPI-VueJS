import { defineStore } from "pinia";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axiosAPI from "@/services/axiosAPI";
import Swal from "sweetalert2";
import { useStoreUserProfile } from "@/stores/user-profile";

const { storeUpdateUser, storeUpdateProfile } = useStoreUserProfile();

export const useManagerBlogStore = defineStore("managerBlogStore", {
  state: () => ({
    users: [],
    profiles: [],
    errors: null,
  }),
  actions: {
    async storeManagerGetUsers() {
      try {
        const response = await fetch(`/api/users`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200].includes(response.status)) {
          this.errors = response.error;
          console.error("store manager get users false ", this.errors);
        }

        const data = await response.json();
        this.users = data.users;
        console.log("store manager get users success ", this.users);
      } catch (error) {
        console.error(error);
      }
    },
    async storeManagerGetProfiles() {
      try {
        const response = await fetch(`/api/user_profiles`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200].includes(response.status)) {
          this.errors = response.error;
          console.error("store manager get profiles false ", this.errors);
        }

        const data = await response.json();
        this.profiles = data.profiles;
        console.log("store manager get profiles success ", this.profiles);
      } catch (error) {
        console.error(error);
      }
    },
  },
});
