<template>
  <div>
    <button
      class="text-blue-600 hover:blue-red-600 transition rounded-lg m-auto flex justify-between"
      type="button"
      @click="open = true"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M12 4.5v15m7.5-7.5h-15"
        />
      </svg>
    </button>

    <TransitionRoot as="template" :show="open">
      <Dialog
        class="fixed inset-0 z-10 flex items-start justify-center mt-20"
        @close="open = false"
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
            class="w-full max-w-2xl bg-white rounded-lg shadow-xl p-6"
          >
            <DialogTitle
              class="text-lg font-semibold text-gray-900 mb-4 flex justify-between items-center"
            >
              <span>เพิ่มช่องทางติดต่อ</span>
              <button
                @click="onAddContact"
                class="text-blue-600 hover:text-blue-800"
              >
                <i class="bi bi-plus-circle"></i> เพิ่มแถว
              </button>
            </DialogTitle>

            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
              <div
                v-for="(contact, index) in contacts"
                :key="index"
                class="grid grid-cols-6 gap-3 items-center bg-gray-100 p-3 rounded"
              >
                <div class="col-span-1">
                  <input
                    type="file"
                    @change="(event) => onSelectFileIcon(event, index)"
                  />
                  <div v-if="contact.preview" class="mt-1">
                    <img
                      :src="contact.preview"
                      class="w-10 h-10 rounded border"
                    />
                  </div>
                </div>

                <input
                  v-model="contact.name"
                  class="col-span-2 form-control"
                  type="text"
                  placeholder="ชื่อช่องทาง"
                />
                <input
                  v-model="contact.url"
                  class="col-span-2 form-control"
                  type="text"
                  placeholder="URL"
                />
                <div class="col-span-1 text-right">
                  <button
                    v-if="contacts.length > 1"
                    @click="onRemoveContact(index)"
                    class="btn btn-sm btn-outline-danger"
                  >
                    ลบ
                  </button>
                </div>
              </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
              <button
                class="btn btn-sm btn-primary"
                @click="onSaveContactProfile"
              >
                บันทึก
              </button>
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="open = false"
              >
                ยกเลิก
              </button>
            </div>
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </div>
</template>
<script setup>
import axiosAPI from "@/services/axiosAPI";
import { ref, reactive } from "vue";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { useStoreUserProfileContacts } from "@/stores/user-profile-contacts";

const { storeAddContacts } = useStoreUserProfileContacts();

const props = defineProps({
  profileID: {
    type: Number,
    required: true,
  },
});

const open = ref(false);

const contacts = reactive([
  {
    name: "",
    url: "",
    fileIcon: [],
    preview: null,
  },
]);

const onAddContact = () => {
  contacts.push({
    name: "",
    url: "",
    fileIcon: [],
    preview: null,
  });
};

const onRemoveContact = (index) => {
  contacts.splice(index, 1);
};

const onSelectFileIcon = (event, index) => {
  const files = event.target.files;
  if (files.length > 0) {
    const file = files[0];
    contacts[index].fileIcon = [file];
    const reader = new FileReader();
    reader.onload = (e) => {
      contacts[index].preview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const onSaveContactProfile = async () => {
  const formData = new FormData();
  formData.append("profile_id", props.profileID);

  contacts.forEach((contact, index) => {
    formData.append(`contacts[${index}][name]`, contact.name);
    formData.append(`contacts[${index}][url]`, contact.url);
    if (contact.fileIcon.length > 0) {
      formData.append(`contacts[${index}][file_icon]`, contact.fileIcon[0]);
    }
  });

  await storeAddContacts(formData);

  open.value = false;
};
</script>
