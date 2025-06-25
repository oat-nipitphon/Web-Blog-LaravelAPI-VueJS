import { defineStore } from "pinia";
import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";
export const usePostStore = defineStore("postStore", {
  state: () => ({
    storePost: [],
    storePosts: [],
    postType: [],
    errors: {},
  }),
  actions: {
    async storeGetPostType() {
      try {
        const response = await fetch(`/api/get_post_type`, {
          method: "GET",
        });

        if (response.status === 200) {
          const data = await response.json();
          this.postType = data.postType;
        }

      } catch (error) {
        console.error("store get post type function error ", error);
      }
    },

    async storeGetPosts() {
      try {
        const response = await fetch(`/api/posts`, {
          method: "GET",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status !== 200) {
          console.log("store response get posts false ", response);
        }
        const data = await response.json();
        this.storePosts = data.posts;
        
      } catch (error) {
        console.error("store get post type function error ", error);
      }
    },

    async storeCreatePost(formData) {
      const result = await Swal.fire({
        title: "Confirm post creation!",
        text: "Do you want to confirm the creation of the post?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Confirm",
      });

      if (!result.isConfirmed) return;

      try {
        const response = await axiosAPI.post("/api/posts", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        const isSuccess = response.status === 200 || response.status === 201;

        await Swal.fire({
          title: isSuccess ? "Successfully" : "Failed",
          text: isSuccess
            ? "Your post has been created successfully."
            : "Failed to create the post.",
          icon: isSuccess ? "success" : "error",
          timer: 1500,
          timerProgressBar: true,
          showConfirmButton: false,
        });

        if (isSuccess) {
          Swal.fire({
            title: "Success",
            text: "Your created post successflly.",
            icon: "success",
            timer: 1200,
          }).then(() => {
            this.router.push({ name: "HomeView" });
          });
        }

        console.log("store response created post false ", response);
      } catch (error) {
        console.error("store response created post function error ", error);
      }
    },

    async storeGetPostShow(post) {
      try {
        const response = await fetch(`/api/posts/${post}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status === 200) {
          const data = await response.json();
          console.log('store get post show ', data.post);
          return data.post;
        }
        console.log("store response get post show false ", response);
      } catch (error) {
        console.error("store response get posts function error ", error);
      }
    },

    async storeUpdatePost(postID, formData) {
      // for (const [key, value] of formData.entries()) {
      //   console.log(`${key}:`, value);
      // }

      const result = await Swal.fire({
        title: "Confirm Edit!",
        text: "Are you sure you want to update this post?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Update",
        cancelButtonText: "Cancel",
      });

      if (!result.isConfirmed) return;

      try {
        formData.append("_method", "PUT");

        const response = await axiosAPI.post(`/api/posts/${postID}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status === 200 || response.status === 201) {
          Swal.fire({
            title: "Update Post Success",
            text: "Your update post successfully.",
            icon: "success",
            timer: 1200,
          });
          this.router.push({ name: "HomeView" });
        }

        console.log("store response updated post false ", response);
      } catch (error) {
        console.error("store response updated post function error ", error);
      }
    },

    async storeDeletePost(id) {
      const result = await Swal.fire({
        title: "Confirm Delete!",
        text: "Are you sure you want to delete this post?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm delete",
        cancelButtonText: "Cancel",
      });

      if (!result.isConfirmed) {
        Swal.close();
        return;
      }

      try {
        const response = await fetch(`/api/posts/${id}`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (response.status === 200) {
          Swal.fire({
            title: "Success",
            text: "Delete post success",
            icon: "success",
            timer: 1200,
            timerProgressBar: true,
          });
          window.location.reload();
        }

        console.log("store response delete post false ", response);
      } catch (error) {
        console.error("store response delete post function ", error);
      }
    },

    // Event Pop
    async storeEventPostPop(postID, profileID, status) {
      try {
        const response = await fetch(
          `/api/posts/event_pop/${postID}/${profileID}/${status}`,
          {
            method: "POST",
            headers: {
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        if (response.status === 200 || response.status === 201) {
          const data = await response.json();
          console.log(
            "store response event post pop success ",
            data.checkStatusPop
          );
        }
        console.log("store response event post pop false ", response);
      } catch (error) {
        console.error("store response event post pop function error ", error);
      }
    },

    // Confirm Store Post
    async storeConfirmStorePost(postID) {
      console.log("store confirm store post");
      const result = await Swal.fire({
        title: "Confirm store post",
        text: "Are you sure you want to store the post?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
      });

      if (!result.isConfirmed) {
        Swal.close();
        return;
      }

      try {
        const response = await fetch(`/api/posts/confirm_store/${postID}`, {
          method: "POST",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (!response.status === 200 || !response.status === 201) {
          Swal.fire({
            title: "Store post false",
            icon: "error",
          }).then(() => {
            console.error(
              "store confirm store post response false ",
              response.error
            );
          });
        }

        Swal.fire({
          title: "Store post success",
          icon: "success",
        });
      } catch (error) {
        console.error("store confirm store post function error ", error);
      }
    },

    //
    async storeGetPostsRecover(profileID) {
      try {
        const response = await fetch(`/api/posts/store_report/${profileID}}`, {
          method: "POST",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        console.log(response);
        if (!response.status === 200) {
          console.log("store get report posts store ", response.error);
          return;
        }

        const data = await response.json();
        return data.posts;
      } catch (error) {
        console.error("store get posts recover function api error ", error);
      }
    },

    async storeConfirmRecoverPost(postID) {
      const result = await Swal.fire({
        title: "ยืนยัน",
        text: "คุณต้องการกู้คืนบทความนี้ใช่หรือไม่ ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "กู้คืน",
        cancelButtonText: "ยกเลิก",
      });

      if (result.isConfirmed) {
        try {
          const res = await fetch(`/api/posts/recover/${postID}`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });

          const data = await res.json();

          if (res.ok) {
            console.log("store confirm recover post success ", res);
            console.log("store confirm recover post success ", data);
          } else {
            console.log("store confirm recover post false ", res);
          }
        } catch (error) {
          console.error(
            "store confirm recover post function api error ",
            error
          );
        }
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.close;
      }
      return;
    },

    async storePostRecoverDelete(id) {
      try {
        const res = await fetch(`/api/posts/delectSelected`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        const data = await res.json();

        if (res.ok) {
          console.log("store post recover delete success ", res);
          console.log("store post recover delete success ", data);
        } else {
          console.log("store post recover delete false ", res);
        }
      } catch (error) {
        console.error("store post recover delete function api error ", error);
      }
    },
  },
});
