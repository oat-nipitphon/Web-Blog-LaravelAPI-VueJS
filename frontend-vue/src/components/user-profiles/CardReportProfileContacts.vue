<template>
  <div
    class="w-full max-w-md m-auto p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700"
  >
    <div class="grid grid-cols-2 items-center mb-4">
      <h5 class="text-base font-semibold text-gray-900 md:text-xl dark:text-white">
        ช่องทางติดต่อ
      </h5>
      <div class="flex justify-end">
        <CardAddContacts :profileID="profileID" />
      </div>
    </div>

    <ul class="space-y-3" v-if="contacts && contacts.length">
      <li v-for="(contact, index) in contacts" :key="contact.id ?? index">
        <a
          :href="contact.url"
          target="_blank"
          rel="noopener"
          class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-500"
        >
          <img
            v-if="contact.imageData"
            :src="'data:image/png;base64,' + contact.imageData"
            alt="Contact Icon"
            class="w-10 h-10 rounded-sm object-cover"
          />
          <div class="flex flex-col text-left">
            <span class="text-sm font-semibold text-gray-900 dark:text-white">
              {{ contact.name }}
            </span>
            <span class="text-xs text-gray-500 dark:text-gray-300">
              {{ contact.url }}
            </span>
          </div>
        </a>

        <!-- Edit Button -->
        <button
          @click="onEdit(contact)"
          class="text-blue-500 hover:text-blue-600 transition rounded-lg mr-2"
          title="Edit"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M11 5h2m-1 0v14m7-7H6"
            />
          </svg>
        </button>

        <!-- Delete Button -->
        <button
          @click="onDelete(contact.id)"
          class="text-red-500 hover:text-red-600 transition rounded-lg"
          title="Delete"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </li>
    </ul>

    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
      ไม่มีข้อมูลช่องทางติดต่อ
    </p>
  </div>

  <!-- Edit Contact Modal -->
  <div
    v-if="isEditModalOpen"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
  >
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
      <h2 class="text-xl font-semibold mb-4">Edit Contact</h2>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Name</label>
          <input
            v-model="formEdit.name"
            class="w-full border rounded px-3 py-2"
            type="text"
          />
        </div>
        <div>
          <label class="block text-sm font-medium">URL</label>
          <input
            v-model="formEdit.url"
            class="w-full border rounded px-3 py-2"
            type="text"
          />
        </div>
      </div>

      <div class="flex justify-end mt-6 space-x-2">
        <button
          @click="isEditModalOpen = false"
          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
        >
          Cancel
        </button>
        <button
          @click="onUpdate"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useStoreUserProfileContacts } from "@/stores/user-profile-contacts";
import CardAddContacts from "@/components/user-profiles/CardAddContacts.vue";

// props
const props = defineProps({
  profileID: {
    type: Number,
    required: true,
  },
  contacts: {
    type: Array,
    required: true,
  },
});

// composable store
const { storeDeleteContact, storeUpdateContact } = useStoreUserProfileContacts();

// modal + form state
const isEditModalOpen = ref(false);
const formEdit = reactive({
  id: null,
  name: "",
  url: "",
});

// Edit button click
const onEdit = (contact) => {
  formEdit.id = contact.id;
  formEdit.name = contact.name;
  formEdit.url = contact.url;
  isEditModalOpen.value = true;
};

// Update (Save)
const onUpdate = async () => {
  const id = formEdit.id;
  const payload = {
    name: formEdit.name,
    url: formEdit.url,
  };

  const success = await storeUpdateContact(id, payload);
  if (success) {
    const index = props.contacts.findIndex((c) => c.id === id);
    if (index !== -1) {
      props.contacts[index].name = payload.name;
      props.contacts[index].url = payload.url;
    }

    isEditModalOpen.value = false;
  }
};

// Delete button click
const onDelete = async (id) => {
  const success = await storeDeleteContact(id);
  if (success) {
    const index = props.contacts.findIndex((c) => c.id === id);
    if (index !== -1) {
      props.contacts.splice(index, 1);
    }
  }
};
</script>
