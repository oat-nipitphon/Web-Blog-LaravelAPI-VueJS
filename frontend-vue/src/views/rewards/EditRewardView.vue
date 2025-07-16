<template>
  <div class="w-full max-w-[80%] bg-white m-auto rounded-lg shadow-lg mt-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Image Upload Section -->
      <div class="flex flex-col space-y-4 bg-white shadow-lg p-5 rounded-lg">
        <p class="text-gray-900 text-3xl font-medium">Upload Image</p>
        <img
          :src="
            imageUrl
              ? 'data:image/png;base64,' + imageUrl
              : 'https://png.pngtree.com/png-clipart/20190920/original/pngtree-file-upload-icon-png-image_4646955.jpg'
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

      <!-- Form Section -->
      <div class="flex flex-col space-y-4 p-5">
        <div class="mt-2">
          <BaseLabel for-id="labelName" text="Name" />
          <BaseInput
            type="text"
            id="name"
            v-model="form.name"
            placeholder="Reward name..."
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
          <BaseSelect
            id="statusID"
            label="Status"
            v-model="form.statusID"
            :options="rewardStatus"
            optionKey="id"
            optionLabel="name"
          />
        </div>

        <div class="flex justify-between mt-5">
          <button
            type="button"
            class="group relative inline-block text-sm font-medium text-white"
            @click="onUpdate"
          >
            <span class="absolute inset-0 border border-blue-600"></span>
            <span
              class="block border border-blue-600 bg-blue-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
            >
              Save
            </span>
          </button>

          <button
            type="button"
            class="group relative inline-block text-sm font-medium text-white"
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
import Swal from "sweetalert2";
import { ref, reactive, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useRewardStore } from "@/stores/reward";
import BaseLabel from "@/components/BaseLabel.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseSelect from "@/components/BaseSelect.vue";

const router = useRouter();
const route = useRoute();
const rewardStatus = ref([]);
const reward = ref({});
const imageFile = ref(null);
const imageUrl = ref(null);

const { storeGetRewardStatus, storeGetReward, storeUpdateReward } =
  useRewardStore();

const form = reactive({
  name: "",
  point: "",
  amount: "",
  statusID: "",
});

const onSelectFileImage = (event) => {
  const file = event.target.files[0];
  if (file) {
    imageFile.value = file;
    const reader = new FileReader();
    reader.onload = () => {
      imageUrl.value = reader.result.split(",")[1];
    };
    reader.readAsDataURL(file);
  }
};

onMounted(async () => {
  rewardStatus.value = await storeGetRewardStatus();
  reward.value = await storeGetReward(route.params.id);

  if (reward.value) {
    form.name = reward.value.name || "";
    form.point = reward.value.point || "";
    form.amount = reward.value.amount || "";
    form.statusID = reward.value.status_id || "";

    if (reward.value.reward_images && reward.value.reward_images.length > 0) {
      imageUrl.value = reward.value.reward_images[0].image_data; // base64 จาก backend
    }
  }
});

const onUpdate = async () => {
  const id = reward.value.id;
  const formData = new FormData();
  formData.append("name", form.name);
  formData.append("point", form.point);
  formData.append("amount", form.amount);
  formData.append("status_id", form.statusID);

  if (imageFile.value) {
    formData.append("image_file", imageFile.value);
  } else if (imageUrl.value && !imageUrl.value.startsWith("http")) {
    const res = await fetch("data:image/png;base64," + imageUrl.value);
    const blob = await res.blob();
    const filename = reward.value.reward_images?.[0]?.image_name || "image.png";
    const file = new File([blob], filename, { type: blob.type });
    formData.append("image_file", file);
  }

  const success = await storeUpdateReward(id, formData);
  if (success) {
    Swal.fire({
      title: "Success",
      text: "update reward successfully.",
      icon: "success",
      timer: 1200,
    }).then(() => {
      Swal.close();
      router.push({
        name: "ManagerReportRewardView",
      });
    });
  }
};

const onCancel = () => {
  router.push({ name: "ManagerReportRewardsView" });
};
</script>
