<template>
  <div class="flex">
    <!-- Trigger Button -->
    <button
      type="button"
      class="btn btn-outline-primary btn-sm"
      @click="openModal"
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

    <!-- Modal -->
    <TransitionRoot as="template" :show="open">
      <Dialog
        as="div"
        class="relative z-50"
        @close="closeModal"
      >
        <div class="fixed inset-0 bg-black/30" aria-hidden="true" />
        <div class="fixed inset-0 flex items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg"
            >
              <DialogTitle class="text-lg font-semibold mb-4">
                อัพเดทรูปโปรไฟล์ผู้ใช้
              </DialogTitle>
              <div class="flex flex-col items-center space-y-4">
                <div class="w-32 h-32 rounded-full border overflow-hidden">
                  <img
                    v-if="previewUrl"
                    :src="previewUrl"
                    alt="Profile Preview"
                    class="object-cover w-full h-full"
                  />
                  <img
                    v-else
                    :src="imageDefault"
                    alt="Default Profile"
                    class="object-cover w-full h-full"
                  />
                </div>
                <input
                  ref="fileInput"
                  type="file"
                  accept="image/*"
                  class="hidden"
                  @change="onSelectImageFile"
                />
                <button
                  type="button"
                  class="px-3 py-1.5 text-sm rounded-md bg-gray-100 hover:bg-gray-200"
                  @click="selectFile"
                >
                  เลือกรูปภาพ
                </button>
              </div>
              <div class="flex justify-end gap-2 mt-6">
                <button
                  class="px-4 py-2 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700"
                  @click="onUploadFile"
                >
                  บันทึก
                </button>
                <button
                  class="px-4 py-2 text-sm bg-gray-300 rounded hover:bg-gray-400"
                  @click="closeModal"
                >
                  ยกเลิก
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import axiosAPI from "@/services/axiosAPI";
import Swal from "sweetalert2";
import { ref } from "vue";
import imageDefault from "@/assets/images/account-profile.png";

const props = defineProps({
  profileID: {
    type: [Array, Number],
    required: true,
  },
});

const open = ref(false);
const previewUrl = ref(null);
const imageFile = ref(null);
const fileInput = ref(null);

function openModal() {
  open.value = true;
}

function closeModal() {
  open.value = false;
  previewUrl.value = null;
  imageFile.value = null;
}

function selectFile() {
  fileInput.value.click();
}

function onSelectImageFile(event) {
  const file = event.target.files[0];
  if (file && file.type.startsWith("image/")) {
    imageFile.value = file;
    previewUrl.value = URL.createObjectURL(file);
  } else {
    Swal.fire("ไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์รูปภาพเท่านั้น", "warning");
  }
}

async function onUploadFile() {
  const formData = new FormData();
  formData.append("profile_id", props.profileID);
  if (imageFile.value) {
    formData.append("image_file", imageFile.value);
  } else {
    Swal.fire("กรุณาเลือกไฟล์", "ยังไม่ได้เลือกไฟล์รูปภาพ", "warning");
    return;
  }

  try {
    const response = await axiosAPI.post(
      "/api/user_profiles/upload_image",
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

      if (![201, 200].includes(response.status)) {
        console.error('upload image profile false', response);
      }

      Swal.fire({
        title: "Success",
        icon: "success",
        timer: 1200,
      }).then(() => {
        Swal.close();
        window.location.reload();
      });

  } catch (error) {
    console.error("Upload error:", error);
  }
}
</script>

<style scoped>
</style>
