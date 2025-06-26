<script setup>
import axiosAPI from "@/services/axiosAPI";
import Swal from "sweetalert2";
import { ref } from "vue";
import imageDefault from "@/assets/images/account-profile.png";

const props = defineProps({
  profileID: {
    type: Number,
    required: true,
  },
});

const imageFile = ref(null);
const imageUrl = ref(null);

const onSelectImageFile = (event) => {
  imageFile.value = event.target.files[0];
  const file = event.target.files[0];
  imageUrl.value = URL.createObjectURL(file);
};

const onUploadFile = async () => {
  const formData = new FormData();
  formData.append("profile_id", props.profileID);
  if (imageFile.value) {
    formData.append("image_file", imageFile.value);
  } else {
    const response = await fetch(imageDefault);
    const blob = await response.blob();
    const file = new File([blob], "default-image.jpg", { type: "image/jpeg" });
    formData.append("image_file", file);
  }

  // Check require file image form data
  // for (const [key, value] of formData.entries()) {
  //   console.log(`${key}:`, value);
  // }
  // return;

  try {
    const response = await axiosAPI.post(
      `/api/user_profiles/upload_image`,
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    if (response.status !== 200) {
      Swal.fire({
        title: "Upload Image false !!",
        text: response.error,
        icon: "error",
        showCancelButton: true,
      }).then(() => {
        Swal.close();
        return;
      });
    }

    Swal.fire({
      title: "Scuuess.",
      text: "Upload image profile successflly.",
      icon: "success",
      timer: 1200,
    }).then(() => {
      Swal.close();
      window.location.reload();
    });
  } catch (error) {
    console.error("function upload image error", error);
  }
};
</script>
<template>
  <div>
    <button
      type="button"
      class="btn btn-sm"
      data-bs-toggle="modal"
      data-bs-target="#exampleModal"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        fill="currentColor"
        class="bi bi-camera"
        viewBox="0 0 16 16"
      >
        <path
          d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z"
        />
        <path
          d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"
        />
      </svg>
    </button>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">อัพเดทรูปโปรไฟล์ผู้ใช้</h5>
            <button
              type="button"
              class="btn-sm btn-close text-gray-900"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>

          <div class="modal-body">
            <div class="flex flex-col items-center space-y-4">
              <div class="flex justify-center w-full">
                <img
                  class="ibox-image-profile shadow-lg rounded-lg"
                  v-show="imageUrl"
                  :src="imageUrl || '../assets/icon/icon-user-default.png'"
                  alt="Profile Image"
                />
              </div>

              <input
                @change="onSelectImageFile"
                type="file"
                accept="image/*"
                id="imageProfile"
                class="block w-full border rounded-lg p-2"
              />
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-sm btn-primary w-full" @click="onUploadFile">
              อัพโหลด
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.ibox-image-profile {
  width: 50%;
  height: auto;
  margin: auto;
}
</style>
