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
      // for (const [key, value] of formData.entries()) {
      //   console.log(`${key}:`, value);
      // }
      // console.log("store update user ", id);
      // return;

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

        if (![200, 201].includes(response.status)) {
          console.log("store update profile", response);
        }

        console.log("store update user success");
        return true;
      } catch (error) {
        console.error("store update user function error ", error);
      }
    },

    async storeUpdateProfile(id, formData) {
      // for (const [key, value] of formData.entries()) {
      //   console.log(`${key}:`, value);
      // }
      // console.log("store update user ", id);
      // return;

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
        const response = await axiosAPI.post(
          `/api/user_profiles/${id}`,
          formData,
          {
            headers: {
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        if (![200, 201].includes(response.status)) {
          console.log("store update profile", response);
        }

        console.log("store update profile success");
        return true;
      } catch (error) {
        console.error("store update profile function error ", error);
      }
    },

    // Profile Followers
    async storeProfileFollowers(profileID, profileIDEvent) {
      try {
        const response = await fetch(
          `/api/profile/followers/${profileID}/${profileIDEvent}`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        if (![200, 201].includes(response.status)) {
          console.error("store profile follower false ", response.error);
          return false;
        }

        const data = await response.json();
        return true;
      } catch (error) {
        console.error("store profile followers error", error);
        return false;
      }
    },

    // Profile Pop
    async storeProfilePop(profileID, profileIDEvent) {
      try {
        const response = await fetch(
          `/api/profile/pop/${profileID}/${profileIDEvent}`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        if (![200, 201].includes(response.status)) {
          console.error(
            "store profile pop failed",
            response.status,
            response.text
          );
          return false;
        }
        const data = await response.json();
        return true;

      } catch (error) {
        console.error("store profile pop error", error);
        return false;
      }
    },
  },
});
