import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useWalletStore = defineStore("walletStore", {
  state: () => ({
    wallets: [],
    errors: [],
  }),
  actions: {

    async storeConfirmSelectdItems (formData) {

      try {
        const response = await fetch(`/api/wellets`, {
          method: "POST",
          headers: {
            authorization: `Bearer ${localStorage.getItem('token')}`
          },
          body: formData,
        });

        if (response.status !== 201 || response.status !== 200) {
          console.error('store confirm selectd items false ', response.error);
        }

        const data = await response.json();
        console.log('store confirm selectd items success ', data);

      } catch (error) {
        console.error('store confirm selectd items function error ', error);
      }

    },

  }
});
