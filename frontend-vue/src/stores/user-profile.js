import axiosAPI from "@/services/axiosAPI";
import { defineStore } from "pinia";
import Swal from "sweetalert2";
export const useStoreUserProfile = defineStore("storeUserProfile", {
  state: () => ({
    userProfile: null,
    userProfiles: [],
    errors: {},
  }),
  actions: {
    
    async storeGetUserStatus() {
      try {
        const response = await fetch(`/api/get_user_status`, {
          method: "GET",
        });

        if (!response.ok) {
          console.log("store get user status false ", response);
        }

        const data = await response.json();
        return data;
      } catch (error) {
        console.error("store get user status function api error ", error);
      }
    },

    async storeGetUserProfile(userProfile) {
      try {
        const response = await fetch(`/api/user_profiles/${userProfile}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (!response.ok) {
          console.log("store get user profile false ", response);
          return;
        }

        const data = await response.json();
        return data.userProfile;
      } catch (error) {
        console.error("store get user profile function error ", error);
      }
    },

    async storeUpdateUser(id, formData) {
      const result = await Swal.fire({
        title: "Confirm Update",
        text: "Are you sure you want to update ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "yes",
        cancelButtonText: "no",
      });

      if (!result.isConfirmed) {
        Swal.close();
        return;
      }
      try {
        formData.append("_method", "PUT");
        const response = await axiosAPI.post(`/api/users/${id}`, formData, {
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (!response.status !== 201 || response.status !== 200) {
          console.log("store update user", response);
        }

        const data = await response.json();
        console.log('store update user', data.user);
        // return data.user;

      } catch (error) {
        console.error("store update user function error ", error);
      }
    },

    async storeUpdateProfile(id, formData) {
      const result = await Swal.fire({
        title: "Confirm Update",
        text: "Are you sure you want to update ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "yes",
        cancelButtonText: "no",
      });

      if (!result.isConfirmed) {
        Swal.close();
        return;
      }
      try {
        formData.append("_method", "PUT");
        const response = await axiosAPI.post(`/api/user_profiles/${id}`, formData, {
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (!response.status === 200 || response.status === 201) {
          console.log("store update profile", response);
        }

        const data = await response.json();
        return data.userProfile;

      } catch (error) {
        console.error("store update profile function error ", error);
      }
    },

  },
});
