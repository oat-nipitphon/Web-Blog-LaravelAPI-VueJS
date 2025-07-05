<template>
  <div>
      <!-- Button edit user -->
      <button
        @click="openModal('editUser')"
        class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="size-2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="m15 11.25 1.5 1.5.75-.75V8.758l2.276-.61a3 3 0 1 0-3.675-3.675l-.61 2.277H12l-.75.75 1.5 1.5M15 11.25l-8.47 8.47c-.34.34-.8.53-1.28.53s-.94.19-1.28.53l-.97.97-.75-.75.97-.97c.34-.34.53-.8.53-1.28s.19-.94.53-1.28L12.75 9M15 11.25 12.75 9"
          />
        </svg>
      </button>
      <!-- Button edit profile -->
      <button
        @click="openModal('editProfile')"
        class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="size-2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="m15 11.25 1.5 1.5.75-.75V8.758l2.276-.61a3 3 0 1 0-3.675-3.675l-.61 2.277H12l-.75.75 1.5 1.5M15 11.25l-8.47 8.47c-.34.34-.8.53-1.28.53s-.94.19-1.28.53l-.97.97-.75-.75.97-.97c.34-.34.53-.8.53-1.28s.19-.94.53-1.28L12.75 9M15 11.25 12.75 9"
          />
        </svg>
      </button>

    <!-- Modal -->
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
              {{ modalType === "editUser" ? "Edit User" : "Edit Profile" }}
            </DialogTitle>

            <!-- Form edit user -->
            <form
              v-if="modalType === 'editUser'"
              @submit.prevent="submitUpdateUser"
            >
              <BaseLabel for-id="email" text="Email" />
              <BaseInput
                id="email"
                type="email"
                placeholder="Enter email"
                v-model="form.email"
              />

              <BaseLabel for-id="username" text="Username" />
              <BaseInput
                id="username"
                type="text"
                placeholder="Enter username"
                v-model="form.username"
              />

              <BaseSelect
                id="statusID"
                label="Status"
                v-model="form.statusID"
                :options="userStatus"
                optionKey="id"
                optionLabel="name"
                placeholder="Select Status"
              />

              <button
                type="submit"
                class="w-full mt-4 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-500"
              >
                Save
              </button>
            </form>

            <!-- Form edit profile -->
            <form v-else @submit.prevent="submitUpdateProfile">
              <BaseSelect
                id="titleName"
                label="Title"
                v-model="form.titleName"
                :options="titleOptions"
                optionKey="id"
                optionLabel="name"
                placeholder="Select Title"
              />

              <BaseLabel for-id="firstName" text="First Name" />
              <BaseInput
                id="firstName"
                type="text"
                placeholder="Enter first name"
                v-model="form.firstName"
              />

              <BaseLabel for-id="lastName" text="Last Name" />
              <BaseInput
                id="lastName"
                type="text"
                placeholder="Enter last name"
                v-model="form.lastName"
              />

              <BaseLabel for-id="nickName" text="Nick Name" />
              <BaseInput
                id="nickName"
                type="text"
                placeholder="Enter nick name"
                v-model="form.nickName"
              />

              <BaseLabel for-id="birthDay" text="Birth Day" />
              <BaseInput id="birthDay" type="date" v-model="form.birthDay" />

              <button
                type="submit"
                class="w-full mt-4 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-500"
              >
                Save
              </button>
            </form>
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ref, reactive, onMounted, watch } from "vue";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { useStoreUserProfile } from "@/stores/user-profile";
import BaseInput from "@/components/BaseInput.vue";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseSelect from "@/components/BaseSelect.vue";

const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
  profile: {
    type: Object,
    default: () => ({}),
  },
});

const { storeGetUserStatus, storeUpdateUser, storeUpdateProfile } =
  useStoreUserProfile();

const open = ref(false);
const modalType = ref(null);
const userStatus = ref([]);
const titleOptions = ref([
  { id: 1, name: "Mr" },
  { id: 2, name: "Miss" },
]);

const form = reactive({
  email: "",
  username: "",
  statusID: "",
  titleName: "",
  firstName: "",
  lastName: "",
  nickName: "",
  birthDay: "",
});

// Load user statuses
onMounted(async () => {
  userStatus.value = await storeGetUserStatus();
});

// Open modal and reset form
const openModal = (type) => {
  modalType.value = type;
  resetForm();
  open.value = true;
};

// Close modal
const closeModal = () => {
  open.value = false;
};

// Reset form fields
const resetForm = () => {
  form.email = props.user?.email ?? "";
  form.username = props.user?.username ?? "";
  form.statusID = props.user?.status?.id ?? "";
  form.titleName = props.profile?.titleName ?? "";
  form.firstName = props.profile?.firstName ?? "";
  form.lastName = props.profile?.lastName ?? "";
  form.nickName = props.profile?.nickName ?? "";
  form.birthDay = props.profile?.birthDay ?? "";
};

// Submit user update
const submitUpdateUser = async () => {
  await storeUpdateUser(props.user.id, form);
  closeModal();
};

// Submit profile update
const submitUpdateProfile = async () => {
  await storeUpdateProfile(props.profile.id, form);
  closeModal();
};
</script>
