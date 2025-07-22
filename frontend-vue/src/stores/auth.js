import { defineStore } from "pinia";
import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";
export const useAuthStore = defineStore("authStore", {
  state: () => ({
    users: null,
    errors: {},
  }),
  actions: {

    // Function Start Authorization Login
    async storeAuth() {
      try {
        const token = localStorage.getItem("token");
        if (!token) return;

        const response = await fetch(`/api/user`, {
          method: "GET",
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
          },
        });

        if ((![200].includes(response.status))) {
          this.users = null;
          return;
        }

        const data = await response.json();
        this.users = data.users;
       
      } catch (error) {
        console.error("store function auth store error:", error);
      }
    },

    // Function Register
    async storeRegister(formData) {
      const result = await Swal.fire({
        title: "Confirm register",
        text: "Do you want to confirm registration?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
      });

      if (result.isConfirmed) {
        try {
          const res = await fetch("/api/register", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
          });

          const data = await res.json();

          if (res.ok) {
            localStorage.setItem("token", data.token);
            this.router.push({ name: "HomeView" });
          } else {
            console.log("store register response false ", data.message);
            await Swal.fire({
              title: "Registration Failed",
              text: "Please try again.",
              icon: "error",
              showCancelButton: true,
              confirmButtonText: "Login",
              cancelButtonText: "Reset Password",
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
            }).then((actionResult) => {
              if (actionResult.isConfirmed) {
                this.router.push({ name: "LoginView" });
              } else {
                this.router.push({ name: "ResetPasswordView" });
              }
            });
          }
        } catch (error) {
          console.error("store register function api error", error);
        }
      } else {
        Swal.close();
      }
    },

    // Function Login
    async storeLogin(formData) {
      try {
        const response = await fetch("/api/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        });

        if (![201, 200].includes(response.status)) {
          Swal.fire({
            title: "Login failed",
            text: "Invalid credentials",
            icon: "error",
            showCancelButton: true,
          }).then(() => {
            this.router.push({ name: "index" });
          });
          return;
        }

        const data = await response.json();
        const token = localStorage.setItem("token", data.token);
        Swal.fire({
          title: "Login successful",
          icon: "success",
          timer: 1200,
          timerProgressBar: true,
          showConfirmButton: false,
        }).then((token) => {
          if (!token) return;
          this.router.push({ name: "HomeView" });
        });
      } catch (error) {
        console.error("store login api function error", error);
      }
    },

    // Function Logout
    async storeLogout() {
      try {
        const response = await fetch("/api/logout", {
          method: "POST",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (![200].includes(response.status)) {
          Swal.fire({
            title: "Logout failed",
            icon: "error",
            timer: 1200,
          });
        }

        Swal.fire({
          title: "Good Bye.",
          icon: "success",
          timer: 1200,
        }).then(() => {
          this.users = null;
          this.errors = {};
          localStorage.removeItem("token");
          this.router.push({ name: "IndexView" });
        });
      } catch (error) {
        console.error("store function api logout error", error);
      }
    },

  },
});
