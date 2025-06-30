import { defineStore } from "pinia";
import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";

export const useStoreUserProfileContacts = defineStore(
  "storeUserProfileContact",
  {
    state: () => ({
      contacts: null,
      errors: {},
    }),
    actions: {
      async storeGetContacts() {
        try {
          const response = await fetch(`/api/user_profile_contacts`, {
            method: "GET",
            headers: {
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });

          if (response.status !== 200) {
            console.error("store get contacts false ", response.error);
          }

          const data = await response.json();
          this.contacts = data.contacts;
          console.log("store get contacts success ", this.contacts);
        } catch (error) {
          console.error("store get contact function error ");
        }
      },

      async storeAddContacts(formData) {
        try {
          const response = await axiosAPI.post(
            `/api/user_profile_contacts`,
            formData,
            {
              headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
              },
            }
          );

          if (response.status !== 201 || response.status !== 200) {
            console.error("store add contacts false ", response.error);
          }

          const data = await response.json();
          console.log("store add contacts successfully ", data.contacts);
        } catch (error) {
          console.error("store add contact function error ", error);
        }
      },

      async storeUpdateContact(id, payload) {

        for (const [key, value] of payload.entries()) {
          console.log(`${key}:`, value);
        }

        console.log('store update contact ', id);

        const result = await Swal.fire({
          title: "Confirm Edit!",
          text: "Are you sure you want to update this contact?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Update",
          cancelButtonText: "Cancel",
        });

        if (!result.isConfirmed) {
          Swal.close();
          return;
        }

        try {
          // formData.append("_method", "PUT");
          const response = await fetch(`/api/user_profile_contacts/${id}`, {
            method: "PUT", // หรือ PATCH ตาม API
            headers: {
              "Content-Type": "application/json",
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
            body: JSON.stringify(payload),
          });

          if (response.status !== 200) {
            const error = await response.json();
            console.error("Update contact failed", error.message || error);
            return false;
          }

          await Swal.fire("Updated", "Contact updated successfully", "success");
          return true;
        } catch (error) {
          console.error("storeUpdateContact error", error);
          return false;
        }
      },

      async storeDeleteContact(id) {
        const result = await Swal.fire({
          title: "Are you sure?",
          text: "Do you want to delete contact this profile?",
          icon: "warning",
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
        });

        if (!result.isConfirmed) {
          Swal.close();
          return;
        }

        try {
          const response = await fetch(`/api/user_profile_contacts/${id}`, {
            method: "DELETE",
            headers: {
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });

          if (![200].includes(response.status)) {
            console.error("delete contacts false", response.error);
            return;
          }

          console.log("delete contacts success", response);
          Swal.fire({
            title: "Success",
            icon: "success",
          });

          window.location.reload();
        } catch (error) {
          console.error("store delete contact function error", error);
        }
      },
    },
  }
);
