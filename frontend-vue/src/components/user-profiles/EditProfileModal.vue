<template>
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
      class="bi bi-gear-fill"
      viewBox="0 0 16 16"
    >
      <path
        d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"
      />
    </svg>
  </button>

  <TransitionRoot as="template" :show="open">
    <Dialog
      class="fixed inset-0 z-10 flex items-start justify-center mt-20"
      @close="closeModal"
    >
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0 scale-95"
        enter-to="opacity-100 scale-100"
        leave="ease-in duration-200"
        leave-from="opacity-100 scale-100"
        leave-to="opacity-0 scale-95"
      >
        <DialogPanel class="w-full max-w-md bg-white rounded-lg shadow-xl p-6">
          <DialogTitle class="text-lg font-semibold text-gray-900 mb-4">
            แก้ไขรายละเอียดโปรไฟล์
          </DialogTitle>

          <div class="space-y-4">
            <div>
              <BaseLabel for-id="titleName" text="Title Name" />
              <BaseSelect
                id="titleName"
                v-model="form.titleName"
                :options="setTitleName"
                optionKey="name"
                optionLabel="name"
              />
            </div>

            <div>
              <BaseLabel for-id="firstName" text="First Name" />
              <BaseInput type="text" id="firstName" v-model="form.firstName" />
            </div>

            <div>
              <BaseLabel for-id="lastName" text="Last Name" />
              <BaseInput type="text" id="lastName" v-model="form.lastName" />
            </div>

            <div>
              <BaseLabel for-id="nickName" text="Nick Name" />
              <BaseInput type="text" id="nickName" v-model="form.nickName" />
            </div>

            <div>
              <BaseLabel for-id="birthDay" text="Birth Day" />
              <BaseInput type="date" id="birthDay" v-model="form.birthDay" />
            </div>

            <div class="flex justify-end gap-2 pt-4">
              <button
                class="px-4 py-2 text-sm bg-gray-300 rounded hover:bg-gray-400"
                @click="closeModal"
              >
                ยกเลิก
              </button>
              <button
                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700"
                @click="submitForm"
              >
                บันทึก
              </button>
            </div>
          </div>
        </DialogPanel>
      </TransitionChild>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";

import { ref, reactive, watch } from "vue";

import BaseInput from "@/components/BaseInput.vue";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseSelect from "@/components/BaseSelect.vue";

import { useStoreUserProfile } from "@/stores/user-profile";

const { storeUpdateProfile } = useStoreUserProfile();

// const emit = defineEmits(["update"]); // ใช้ emit กลับไปยัง parent

const open = ref(false);
const openModal = () => (open.value = true);
const closeModal = () => (open.value = false);

const props = defineProps({
  profile: Object,
});

const form = reactive({
  profileID: "",
  titleName: "",
  firstName: "",
  lastName: "",
  nickName: "",
  birthDay: "",
});

const setTitleName = ref([
  { id: 1, name: "Mr" },
  { id: 2, name: "Miss" },
]);

watch(
  () => props.profile,
  (row) => {
    if (row) {
      form.profileID = row.id || "";
      form.titleName = row.titleName || "";
      form.firstName = row.firstName || "";
      form.lastName = row.lastName || "";
      form.nickName = row.nickName || "";
      form.birthDay = row.birthDay || "";
    }
  },
  { immediate: true }
);

const submitForm = async () => {
  // emit("update", { ...form }); // ส่งข้อมูลกลับไปให้ parent
  const id = form.profileID;
  const formData = new FormData();
  formData.append("profile_id", form.profileID);
  formData.append("title_name", form.titleName);
  formData.append("first_name", form.firstName);
  formData.append("last_name", form.lastName);
  formData.append("nick_name", form.nickName);
  formData.append("birth_day", form.birthDay);
  //   for (const [key, value] of formData.entries()) {
  //   console.log(`${key}:`, value);
  // }
  // return;
  const success = await storeUpdateProfile(id, formData);

  if (success) {
  closeModal();
  window.location.reload();
  } else {
    console.log('edit profile false');
  }


};
</script>
