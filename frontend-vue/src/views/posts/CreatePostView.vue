<template>
  <div class="bg-white rounded-xl shadow-lg mt-5 max-w-5xl m-auto p-10">

    <div class="m-auto">
      <PageHeader title="Create post" />
    </div>

    <div class="mt-3">
      <BaseLabel for-id="labelPostTitle" text="Title" />
      <BaseInput
        id="postTitle"
        type="text"
        placeholder="input title ..."
        v-model="form.title"
      />
    </div>

    <div class="mt-3">
      <BaseLabel for-id="labelPostContent" text="Content" />
      <!-- Editor Create Content -->
    </div>

    <div class="mt-3">
      <BaseLabel for-id="labelRefer" text="Refer" />
      <BaseInput
        id="postRefer"
        type="text"
        placeholder="input refer ..."
        v-model="form.refer"
      />
    </div>

    <div class="mt-3">
      <div class="grid mt-5" v-if="isSelectType">
        <BaseLabel for-id="labelSeleteType" text="Select type" />
        <BaseSelect
          id="postType"
          @change="onSelectType"
          v-model="form.typeID"
          :options="postTypes"
          optionKey="id"
          optionLabel="name"
          placeholder="Please select a type"
        >
          <option value="new">add type +</option>
        </BaseSelect>
      </div>

      <div class="grid grid-rows-2 mt-5" v-if="isNewType">
        <div class="grid grid-cols-2">
          <div>
            <BaseLabel for-id="labelNewType" text="Add type" />
          </div>
          <div v-if="isButtonSelect" class="flex justify-end mt-auto mb-auto">
            <button @click="onSelectAgain" class="btn btn-sm">
              <BaseLabel for-id="labelNewType" text="Select type again" />
            </button>
          </div>
        </div>
        <BaseInput
          id="postNewType"
          type="text"
          v-model="form.newType"
          placeholder="input new type .."
        />
      </div>
    </div>

    <div class="mt-3">
      <BaseLabel for-id="labelImage" text="Image File" />
      <BaseInputFileImageCover
        label="Cover photo"
        input-id="cover-upload"
        @update:file="FileImageUploadCover = $event"
      />
    </div>

    <div class="flex justify-end mt-5">
      <button
        type="submit"
        class="m-auto group relative inline-block text-sm font-medium text-white focus:ring-3 focus:outline-hidden"
        @click="onCreatePost"
      >
        <span class="absolute inset-0 border border-blue-600"></span>
        <span
          class="block border border-blue-600 bg-blue-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
        >
          Save
        </span>
      </button>

      <button
        type="submit"
        class="m-auto group relative inline-block text-sm font-medium text-white focus:ring-3 focus:outline-hidden"
        @click="onCancel"
      >
        <span class="absolute inset-0 border border-red-600"></span>
        <span
          class="block border border-red-600 bg-red-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
        >
          Cancel
        </span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import { SimpleEditor } from "@/components/tiptap-templates/simple/simple-editor";

import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";
import imageDefault from "@/assets/images/keyboard.jpg";
import PageHeader from "@/components/PageHeader.vue";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseSelect from "@/components/BaseSelect.vue";
import BaseTextArea from "@/components/BaseTextArea.vue";
import BaseInputFileImageCover from "@/components/FileImageUploadCover.vue";
import TiptapEditor from "@/components/posts/TiptapEditor.vue";

const router = useRouter();
const authAuth = useAuthStore();
const postStore = usePostStore();
const { storeCreatePost } = usePostStore();
const { storeGetPostType } = usePostStore();
const { postTypes } = storeToRefs(postStore);

const isSelectType = ref(true);
const isButtonSelect = ref(false);
const isNewType = ref(false);
const FileImageUploadCover = ref(null);

const form = ref({
  title: "",
  content: "",
  refer: "",
  typeID: "",
  newType: "",
});

const onSelectType = () => {
  if (form.value.typeID === "new") {
    isSelectType.value = false;
    isNewType.value = true;
    isButtonSelect.value = true;
    form.value.typeID = 0;
  } else {
    form.value.newType = 0;
  }
};

const onSelectAgain = () => {
  isSelectType.value = true;
  isNewType.value = false;
  isButtonSelect.value = false;
};

const onCreatePost = async () => {
  if (!form.value.title.trim()) {
    Swal.fire("กรุณากรอก Title", "", "warning");
    return;
  }

  if (!form.value.content.trim()) {
    Swal.fire("กรุณากรอก Content", "", "warning");
    return;
  }

  if (!form.value.typeID && !form.value.newType.trim()) {
    Swal.fire("กรุณาเลือกหรือเพิ่ม Type", "", "warning");
    return;
  }

  if (!FileImageUploadCover.value) {
    Swal.fire("กรุณาอัปโหลดรูปภาพ", "", "warning");
    return;
  }

  const formData = new FormData();

  formData.append("profile_id", authAuth.users.userProfile.id);
  formData.append("type_id", form.value.typeID);
  formData.append("new_type", form.value.newType);
  formData.append("title", form.value.title);
  formData.append("content", form.value.content);
  formData.append("refer", form.value.refer);

  if (FileImageUploadCover.value) {
    formData.append("image_file", FileImageUploadCover.value);
  } else {
    const response = await fetch(imageDefault);
    const blob = await response.blob();
    const file = new File([blob], "default-image.jpg", { type: "image/jpeg" });
    formData.append("image_file", file);
  }

  const success = await storeCreatePost(formData);

  if (success !== true) return;

  Swal.fire({
    title: "Success",
    text: "Your created post successflly.",
    icon: "success",
    timer: 1200,
  }).then(() => {
    this.router.push({ name: "HomeView" });
    return;
  });

  // Check data require form data
  // for (const [key, value] of formData.entries()) {
  //   console.log(`${key}:`, value);
  // }
};

const onCancel = () => {
  router.push({ name: "HomeView" });
};

onMounted(async () => {
  await storeGetPostType();
});
</script>

<style scoped>
/* Image Preview Styles */
.ibox-image-post {
  margin: auto;
  margin-top: 30px;
  margin-bottom: 30px;
  width: 90%;
  height: 350px;
  object-fit: cover;
}
</style>
