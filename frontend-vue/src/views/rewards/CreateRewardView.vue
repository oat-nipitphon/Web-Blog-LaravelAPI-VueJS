<template>
  <div class="w-full max-w-[80%] bg-white m-auto rounded-lg shadow-lg mt-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="flex flex-col space-y-4 bg-white shadow-lg p-5 rounded-lg">
        <p class="text-gray-900 text-3xl font-medium">Upload Image</p>
        <img
          :src="
            imageUrl ||
            'https://png.pngtree.com/png-clipart/20190920/original/pngtree-file-upload-icon-png-image_4646955.jpg'
          "
          alt="Image Preview"
          class="w-full h-[250px] object-cover rounded-lg border border-gray-300"
        />
        <input
          id="fileImage"
          type="file"
          accept="image/*"
          class="mt-5 w-full h-[40px] text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
          @change="onSelectFileImage"
        />
      </div>
      <div class="flex flex-col space-y-4 p-5">
        <div class="mt-2">
          <BaseLabel for-id="labelName" text="Name" />
          <BaseInput
            type="text"
            id="name"
            v-model="form.name"
            placeholder="reward name ?"
          />
        </div>

        <div class="mt-2">
          <BaseLabel for-id="labelPoint" text="Point" />
          <BaseInput
            type="number"
            id="point"
            v-model="form.point"
            placeholder="999"
          />
        </div>

        <div class="mt-2">
          <BaseLabel for-id="labelAmount" text="Amount" />
          <BaseInput
            type="number"
            id="amount"
            v-model="form.amount"
            placeholder="1234..."
          />
        </div>

        <div class="mt-2">
          <BaseSelect> </BaseSelect>
        </div>

        <div class="flex justify-end mt-5">
          <button
            type="submit"
            class="m-auto group relative inline-block text-sm font-medium text-white focus:ring-3 focus:outline-hidden"
            @click="onSave"
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
    </div>
  </div>
</template>
<script setup>
import axiosAPI from "@/services/axiosAPI";
import Swal from "sweetalert2";
import { ref } from "vue";
import { useRouter } from "vue-router";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseSelect from "@/components/BaseSelect.vue";
import { useRewardStore } from "@/stores/reward";

const router = useRouter();

const { storeCreateReward } = useRewardStore();

const form = ref({
  name: "",
  point: "",
  amount: "",
  status: "true",
});

const imageFile = ref(null);
const imageUrl = ref(null);

const onSelectFileImage = (event) => {
  const file = event.target.files[0];
  if (file) {
    imageFile.value = file;
    imageUrl.value = URL.createObjectURL(file);
  }
};

const onSave = async () => {
  const formData = new FormData();
  formData.append("name", form.value.name);
  formData.append("point", form.value.point);
  formData.append("amount", form.value.amount);
  formData.append("type", form.value.type);
  formData.append("status", form.value.status);
  if (imageFile.value) {
    formData.append("imageFile", imageFile.value);
  }

  for (const [key, value] of formData.entries()) {
    console.log(`${key}:`, value);
  }
  return;
  await storeCreateReward(formData);
};

const onCancel = () => {
  router.push({
    name: 'ManagerReportRewardsView'
  });
};
</script>
