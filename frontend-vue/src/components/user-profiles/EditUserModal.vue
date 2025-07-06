<template>
  <div>
    <!-- ปุ่มเปิด Modal -->
    <button type="button" class="btn btn-outline-primary btn-sm" @click="openModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill"
        viewBox="0 0 16 16">
        <path
          d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
      </svg>
    </button>

    <!-- Modal ด้วย Headless UI -->
    <TransitionRoot as="template" :show="open">
      <Dialog class="fixed inset-0 z-10 overflow-y-auto" @close="closeModal">
        <div class="flex items-center justify-center min-h-screen p-4">
          <TransitionChild enter="ease-out duration-300" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95" as="template">
            <DialogPanel class="w-full max-w-md rounded bg-white p-6 shadow-xl">
              <DialogTitle class="text-lg font-bold text-gray-900 mb-4">
                แก้ไขข้อมูลผู้ใช้
              </DialogTitle>

              <input type="hidden" v-model="form.userID" />

              <div class="mb-4">
                <BaseLabel for-id="labelStatus" text="Status :: " />
                <BaseSelect id="statusID" v-model="form.statusID" :options="userStatus" optionKey="id"
                  optionLabel="name" />
              </div>

              <div class="mb-4">
                <BaseLabel for-id="labelEmail" text="Email :: " />
                <BaseInput type="email" id="email" v-model="form.email" />
              </div>

              <div class="mb-4">
                <BaseLabel for-id="labelUsername" text="Username :: " />
                <BaseInput type="text" id="username" v-model="form.username" />
              </div>

              <div class="flex justify-end mt-4 gap-2">
                <button type="button" class="btn btn-secondary" @click="closeModal">
                  ยกเลิก
                </button>
                <button @click="submitUpdate" type="submit" class="btn btn-primary">
                  อัพเดท
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
import { ref, reactive, watch } from "vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseSelect from "@/components/BaseSelect.vue";
import { useStoreUserProfile } from "@/stores/user-profile";
const { storeUpdateUser } = useStoreUserProfile();

const open = ref(false);
const openModal = () => (open.value = true);
const closeModal = () => (open.value = false);

const props = defineProps({
  user: Object,
  userStatus: Array,
});

const form = reactive({
  userID: "",
  email: "",
  username: "",
  statusID: "",
  statusName: "",
});

watch(
  () => props.user,
  (row) => {
    if (row) {
      form.userID = row.id || "";
      form.email = row.email || "";
      form.username = row.username || "";
      form.statusID = row.userStatus?.id || "";
      form.statusName = row.userStatus?.statusName || "";
    }
  },
  { immediate: true }
);

const submitUpdate = async () => {
  const id = form.userID;
  console.log("submitUpdateUser ", id);
  const formData = new FormData();
  formData.append("user_id", form.userID);
  formData.append("email", form.email);
  formData.append("username", form.username);
  formData.append("status_id", form.statusID);

  // for (const [key, value] of formData.entries()) {
  //   console.log(`${key}:`, value);
  // }
  // return;

  const success = await storeUpdateUser(id, formData);

  if (success) {
    closeModal();
    window.location.reload();
  } else {
    console.log('edit profile false');
  }
};
</script>
