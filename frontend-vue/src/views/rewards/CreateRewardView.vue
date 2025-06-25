<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
</script>
<template>
  <div class="m-auto">
    <h1>Create Reward View</h1>
  </div>
</template>
<!-- <script setup>
import Swal from 'sweetalert2'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useRewardStore } from '@/stores/reward'
import axiosAPI from '@/services/axiosAPI'
const router = useRouter()
const { newReward } = useRewardStore()

const form = ref({
  name: '',
  point: '',
  amount: '',
  type: 'reward',
  status: 'true',
})

const imageFile = ref(null)
const imageUrl = ref(null)

const onSelectFileImage = event => {
  const file = event.target.files[0]

  if (file) {
    imageFile.value = file
    imageUrl.value = URL.createObjectURL(file)
  }
}

const onSave = async () => {
  console.log('imageFile', imageFile.value)
  const formData = new FormData()
  formData.append('name', form.value.name)
  formData.append('point', form.value.point)
  formData.append('amount', form.value.amount)
  formData.append('type', form.value.type)
  formData.append('status', form.value.status)
  if (imageFile.value) {
    formData.append('imageFile', imageFile.value)
  }

  const result = await Swal.fire({
    title: 'ยืนยันการบันทึก',
    text: 'คุณต้องการเพิ่มรางวัลใหม่นี้ใช่หรือไม่?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'บันทึก',
    cancelButtonText: 'ยกเลิก',
  })

  if (result.isConfirmed) {
    try {
      const res = await axiosAPI.post(`/api/reward/newRewards`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      })

      if (!res.status === 200) {
        console.log('new reward false', res)
      }
      Swal.fire({
        title: 'สำเร็จ',
        text: 'เพิ่มรางวัลสำเร็จ',
        icon: 'success',
        timer: 1500,
      }).then(() => {
        router.push({ name: 'AdminManagerReward' })
      });
        
    } catch (error) {
      console.error('new reward function api error ', error)
    }
  }
}

const onCancel = () => {
  router.push({ name: 'DashboardRewardView' })
}
</script>
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
        <div>
          <label class="block mb-2 mt-2 text-1xl font-medium text-gray-900"
            >Name</label
          >
          <input
            v-model="form.name"
            type="text"
            class="w-full h-[40px] p-2.5 rounded-lg border text-sm bg-gray-50 border-gray-300"
          />
        </div>
        <div>
          <label class="block mb-2 mt-2 text-1xl font-medium text-gray-900"
            >Point</label
          >
          <input
            v-model="form.point"
            type="text"
            class="w-full h-[40px] p-2.5 rounded-lg border text-sm bg-gray-50 border-gray-300"
          />
        </div>

        <div>
          <label class="block mb-2 mt-2 text-1xl font-medium text-gray-900"
            >Amount</label
          >
          <input
            v-model="form.amount"
            type="text"
            class="w-full h-[40px] p-2.5 rounded-lg border text-sm bg-gray-50 border-gray-300"
          />
        </div>

        <div>
          <label class="block mb-2 mt-2 text-1xl font-medium text-gray-900"
            >Type</label
          >
          <select
            v-model="form.type"
            class="w-full h-[40px] p-2.5 rounded-lg border text-sm bg-gray-50 border-gray-300"
          >
            <option value="reward">reward</option>
          </select>
        </div>

        <div>
          <label class="block mb-2 mt-2 text-1xl font-medium text-gray-900"
            >Status</label
          >
          <select
            v-model="form.status"
            class="w-full h-[40px] p-2.5 rounded-lg border text-sm bg-gray-50 border-gray-300"
          >
            <option value="true">true</option>
            <option value="false">false</option>
          </select>
        </div>

        <div class="flex justify-end space-x-4 mt-5">
          <button @click="onSave" class="btn btn-primary">Save</button>
          <button @click="onCancel" class="btn btn-danger">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template> -->
