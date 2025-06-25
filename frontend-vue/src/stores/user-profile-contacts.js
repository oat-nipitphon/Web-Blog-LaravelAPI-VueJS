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

      async storeGetContacts () {

        try {

          const response = await fetch(`/api/user_profile_contacts`, {
            method: "GET",
            headers: {
              authorization: `Bearer ${localStorage.getItem('token')}`
            }
          });

          if (response.status !== 200) {
            console.error('store get contacts false ', response.error);
          }

          const data = await response.json();
          this.contacts = data.contacts;
          console.log('store get contacts success ', this.contacts);

        } catch(error) {
          console.error('store get contact function error ');
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
            console.error('store add contacts false ', response.error);
          }

          const data = await response.json();
          console.log("store add contacts successfully ", data.contacts);
          
        } catch (error) {
          console.error("store add contact function error ", error);
        }
      },
    },
  }
);
