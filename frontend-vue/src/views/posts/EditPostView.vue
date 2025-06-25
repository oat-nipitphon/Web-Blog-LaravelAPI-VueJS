<template>
  <div class="bg-white rounded-xl shadow-lg mt-5 max-w-5xl m-auto p-10">
    <!-- Page Header -->
    <div class="m-auto">
      <PageHeader title="Create post" />
    </div>

    <!-- Title -->
    <div class="mt-3">
      <BaseLabel for-id="labelPostTitle" text="Title" />
      <BaseInput
        id="postTitle"
        type="text"
        placeholder="input title ..."
        v-model="formUpdate.title"
      />
    </div>

    <!-- Content -->
    <div class="mt-3">
      <BaseLabel for-id="labelPostContent" text="Content" />
      <BaseTextArea
        id="postContent"
        type="text"
        placeholder="input content ..."
        v-model="formUpdate.content"
      />
    </div>

    <!-- Refer -->
    <div class="mt-3">
      <BaseLabel for-id="labelRefer" text="Refer" />
      <BaseInput
        id="postRefer"
        type="text"
        placeholder="input refer ..."
        v-model="formUpdate.refer"
      />
    </div>

    <!-- Type -->
    <div class="mt-3">
      <!-- Select  -->
      <div class="grid mt-5" v-if="isSelectType">
        <BaseLabel for-id="labelSeleteType" text="Select type" />
        <BaseSelect
          id="postType"
          @change="onSelectType"
          v-model="formUpdate.typeID"
          :options="postTypes"
          optionKey="id"
          optionLabel="name"
          placeholder="Please select a type"
        >
          <option value="new">add type +</option>
        </BaseSelect>
      </div>

      <!-- Add -->
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
          v-model="formUpdate.newType"
          placeholder="input new type .."
        />
      </div>
    </div>

    <!-- Image -->
    <div class="mt-3">
      <BaseLabel for-id="labelImage" text="Image File" />
      <BaseInputFileImageCover
        label="Cover photo"
        input-id="cover-upload"
        :initial-image="FileImageUrl"
        @update:file="FileImageUploadCover = $event"
      />
      <div v-if="FileImageUrl" class="bg-white mt-3 rounded-xl">
        <img :src="FileImageUrl" alt="Image Preview" class="ibox-image-post" />
      </div>
    </div>

    <!-- Button Event -->
    <div class="flex justify-end mt-5">
      <button
        type="submit"
        class="m-auto group relative inline-block text-sm font-medium text-white focus:ring-3 focus:outline-hidden"
        @click="onUpdate"
      >
        <span class="absolute inset-0 border border-blue-600"></span>
        <span
          class="block border border-blue-600 bg-blue-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
        >
          Update
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
import { ref, onMounted, reactive, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";

import Swal from "sweetalert2";
import axiosAPI from "@/services/axiosAPI";
import imageDefault from "@/assets/images/keyboard.jpg";
import PageHeader from "@/components/PageHeader.vue";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseSelect from "@/components/BaseSelect.vue";
import BaseTextArea from "@/components/BaseTextArea.vue";
import BaseInputFileImageCover from "@/components/FileImageUploadCover.vue";

const router = useRouter();
const route = useRoute();
const authAuth = useAuthStore();
const postStore = usePostStore();
const postTypes = ref([]);
const isSelectType = ref(true);
const isButtonSelect = ref(false);
const isNewType = ref(false);

const FileImageUrl = ref(null);
const FileImageUploadCover = ref(null);
const previewUrl = ref(null);

const postEdit = ref([]);
const formUpdate = ref({
  title: "",
  content: "",
  refer: "",
  typeID: "",
  newType: "",
});

const { storeGetPostShow, storeUpdatePost } = usePostStore();

const onSelectType = () => {
  if (formUpdate.value.typeID === "new") {
    isSelectType.value = false;
    isNewType.value = true;
    isButtonSelect.value = true;
    formUpdate.value.typeID = 0;
  } else {
    formUpdate.value.newType = 0;
  }
};

const onSelectAgain = () => {
  isSelectType.value = true;
  isNewType.value = false;
  isButtonSelect.value = false;
};

const onCancel = () => {
  router.push({ name: "HomeView" });
};

const getPostTypes = async () => {
  try {
    const res = await axiosAPI.get("/api/get_post_types");
    if (!res.status === 200) {
      console.log("get post type false", res);
    }
    return res.data;
  } catch (error) {
    console.error("get post type function error ", error);
  }
};

onMounted(async () => {
  postTypes.value = await getPostTypes();

  postEdit.value = await storeGetPostShow(route.params.id);
  if (postEdit.value) {
    formUpdate.value.postID = postEdit.value.postID || "";
    formUpdate.value.title = postEdit.value.title || "";
    formUpdate.value.content = postEdit.value.content || "";
    formUpdate.value.refer = postEdit.value.refer || "";
    formUpdate.value.typeID = postEdit.value.postType.id || "";
    formUpdate.value.typeName = postEdit.value.postType.name || "";
    formUpdate.value.newType = postEdit.value.newType || "";

    FileImageUploadCover.value =
      postEdit.value.postImage?.map((postImg) => ({
        id: postImg.id,
        imageData: postImg.imageData,
      })) || [];
  }
});

watch(
  FileImageUploadCover,
  (newFile) => {
    if (newFile && newFile instanceof File) {
      const reader = new FileReader();
      reader.onload = (e) => {
        FileImageUrl.value = e.target.result;
      };
      reader.readAsDataURL(newFile);
    } else if (
      Array.isArray(newFile) &&
      newFile.length &&
      newFile[0].imageData
    ) {
      FileImageUrl.value = `data:image/png;base64,${newFile[0].imageData}`;
    }
  },
  { immediate: true }
);

const onUpdate = async () => {

  const formData = new FormData();
  formData.append("profile_id", authAuth.users.userProfile.id);
  formData.append("post_id", formUpdate.value.postID);
  formData.append("title", formUpdate.value.title);
  formData.append("content", formUpdate.value.content);
  formData.append("refer", formUpdate.value.refer);
  formData.append("type_id", formUpdate.value.typeID);
  formData.append("new_type", formUpdate.value.newType);

  if (FileImageUploadCover.value) {
    formData.append("image_file", FileImageUploadCover.value);
  } else {
    const response = await fetch(imageDefault);
    const blob = await response.blob();
    const file = new File([blob], "default-image.jpg", { type: "image/jpeg" });
    formData.append("image_file", file);
  }

  const postID = formUpdate.value.postID;
  await storeUpdatePost(postID, formData);

};

</script>
<style scoped>
.ibox-image-post {
  margin: auto;
  margin-top: 30px;
  margin-bottom: 30px;
  width: 90%;
  height: 350px;
  object-fit: cover;
}
</style>
