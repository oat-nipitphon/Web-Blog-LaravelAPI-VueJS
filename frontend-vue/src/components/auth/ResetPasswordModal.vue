<script setup>
import { ref } from "vue";
import { RouterLink, useRouter } from "vue-router";
import axiosAPI from "@/services/axiosAPI";
import BaseLabel from "../BaseLabel.vue";
import BaseInput from "../BaseInput.vue";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";

const open = ref(false);
const modalType = ref('resetPassword');

const formReset = ref({
  email: null,
  password: null,
  confirmPassword: null,
});

const passwordConfirmError = computed(() => {
  if (!formReset.confirmPassword)
    return { message: "Please confirm your password.", valid: false };
  if (formReset.password !== formReset.confirmPassword)
    return { message: "Passwords do not match.", valid: false };
  return { message: "Passwords match.", valid: true };
});

const openModal = (type) => {
    modalType.value = type;
    open.value = true;
}

const closeModal = () => {
  open.value = false;
  resetForm();
};

const onReset = async () => {
  console.log("reset check form", formReset.value);

  try {
    const response = await axiosAPI.post(`/api/reset-password`, formReset);

    console.log('on reset () ', response);

    if (!response.ok) {
        console.log('reset password false ');
    }

    const data = await response.json();
    console.log('reset password success', data.statusReset);

  } catch (error) {
    console.error(error);
  }
};

const onCancel = async () => {
  Object.assign(formReset, {
    email: "",
    password: "",
    confirmPassword: "",
  });
  passwordConfirmError.value = "";
};

</script>
<template>

    <div class="m-auto flex justify-center">
        <button
        class="inline-block rounded border border-indigo-600 bg-indigo-600 px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-indigo-700""
        @click="openModal('re')"
        >

        </button>
    </div>
  <div class="w-50 flex justify-center mt-10 bg-white shadow-lg rounded-lg">
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
          <DialogPanel
            class="w-full max-w-md bg-white rounded-lg shadow-xl p-6"
          >
            <DialogTitle
              as="h3"
              class="text-lg font-semibold text-gray-900 mb-4"
            >
            {{ modalType === 'resetPassword' }}
            </DialogTitle>
            <div class="m-auto">
              <BaseLabel for-id="email" text="Email :" />
              <BaseInput
                id="email"
                type="email"
                placeholder="account email ..."
                v-model="formReset.email"
              />
            </div>
            <div class="m-auto">
              <BaseLabel for-id="password" text="Password :" />
              <BaseInput
                id="password"
                type="password"
                v-model="formReset.password"
              />
            </div>
            <div class="m-auto">
              <BaseLabel for-id="confirmPassword" text="Confirm Password :" />
              <BaseInput
                id="confirmPassword"
                type="password"
                v-model="formReset.confirmPassword"
              />
              <p class="w-full m-auto">
                {{ passwordConfirmError.message }}
              </p>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                @click="onReset"
                class="block border border-blue-600 bg-blue-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
              >
                Reset
              </button>
              <button
                type="submit"
                @click="onCancel"
                class="block border border-red-600 bg-red-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
              >
                Cancel
              </button>
            </div>
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </div>
</template>
