<template>
  <div class="container bg-white shadow-lg rounded-lg mx-auto p-6">
    <div class="w-full">
      <h2 class="ml-2 p-2">กู้คืนบทความ</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-200 text-gray-700 uppercase">
          <tr>
            <th class="p-3 text-center">
              <input v-if="posts.length" type="checkbox" :checked="selectAll" @change="selectAll = !selectAll" class="m-auto" />
              <button v-if="selectedItems.length" @click="toggleAllCancel" class="ml-2 text-red-600 hover:underline text-xs">
                ยกเลิก
              </button>
            </th>
            <th class="p-3 text-center">หัวข้อ</th>
            <th class="p-3 text-center">วันที่จัดเก็บ</th>
            <th class="p-3 text-center">กู้คืน</th>
            <th class="p-3 text-center">ลบถาวร</th>
          </tr>
        </thead>
        <tbody v-if="posts.length">
          <tr v-for="post in posts" :key="post.id" class="border-b hover:bg-gray-100 transition">
            <td class="p-3 text-center">
              <input type="checkbox" :value="post.id" v-model="selectedItems" class="m-auto" />
            </td>
            <td class="p-3 text-center">{{ post.post_title }}</td>
            <td class="p-3 text-center text-gray-500">{{ formatDateTime(post.created_at) }}</td>
            <td class="p-3 text-center">
              <button @click="apiRecoverPost(post.id)" class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-1.5 px-3 rounded-lg shadow-md transition duration-300">
                กู้คืน
              </button>
            </td>
            <td class="p-3 text-center">
              <button @click="onDelete(post.id)" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-1.5 px-3 rounded-lg shadow-md transition duration-300">
                ลบถาวร
              </button>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="5" class="text-center py-5 text-red-600 font-medium">
              ไม่มีบทความ ที่สามารถกู้คืนได้ !
            </td>
          </tr>
        </tbody>
      </table>
      <div class="flex justify-start mt-4 space-x-2" v-if="selectedItems.length">
        <button class="bg-yellow-300 hover:bg-yellow-400 text-gray-900 text-sm font-medium py-2 px-4 rounded-lg shadow-md transition duration-300" @click="onRecoverSelected">
          กู้คืนบทความที่เลือก
        </button>
        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md transition duration-300" @click="onDeleteSelected">
          ลบบทความที่เลือก
        </button>
        <button class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md transition duration-300" @click="toggleAllCancel">
          ยกเลิก
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import Swal from 'sweetalert2'
import axiosAPI from '@/services/axiosAPI'
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'
import { usePostStore } from '@/stores/post'

const authStore = useAuthStore()
const { users } = storeToRefs(authStore)
const { storeGetPostsRecover, storeDeletePost } = usePostStore()

const route = useRoute()
const posts = ref([])
const selectedItems = ref([])

const profileID = ref(authStore.users?.userProfile?.id || '')

onMounted(async () => {
  posts.value = await storeGetPostsRecover(route.params.profileID)
  posts.value = posts.value.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
})

const selectAll = computed({
  get: () => posts.value.length > 0 && selectedItems.value.length === posts.value.length,
  set: value => {
    selectedItems.value = value ? posts.value.map(post => post.id) : []
  },
})

const formatDateTime = (dateTime) => {
  if (!dateTime) return '-'
  const date = new Date(dateTime)
  const year = date.getFullYear() + 543
  const month = date.getMonth()
  const day = date.getDate()
  const hour = date.getHours().toString().padStart(2, '0')
  const minute = date.getMinutes().toString().padStart(2, '0')
  const second = date.getSeconds().toString().padStart(2, '0')
  const thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม']
  return `${day} ${thaiMonths[month]} ${year} เวลา ${hour}:${minute}:${second} น.`
}

const toggleAllCancel = () => {
  selectedItems.value = []
}

const onDelete = async id => {
  const result = await Swal.fire({
    title: 'ยืนยันลบบทความ!',
    text: 'คุณต้องการลบบทความนี้ใช่หรือไม่?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก'
  })

  if (result.isConfirmed) {
    try {
      await storeDeletePost(id)
      posts.value = posts.value.filter(post => post.id !== id)
    } catch (error) {
      console.error('Error deleting post', error)
    }
  }
}

const onRecoverSelected = async () => {
  const result = await Swal.fire({
    title: 'ยืนยันกู้คืนบทความ!',
    text: 'คุณต้องการกู้คืนบทความทั้งหมดที่เลือกใช่หรือไม่?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก'
  })

  if (result.isConfirmed) {
    const token = localStorage.getItem('token')
    if (token) {
      await axiosAPI.post('/api/posts/recoverSelected', { ids: selectedItems.value }, {
        headers: { Authorization: `Bearer ${token}` }
      })
      posts.value = posts.value.filter(post => !selectedItems.value.includes(post.id))
      selectedItems.value = []
    }
  }
}

const onDeleteSelected = async () => {
  const result = await Swal.fire({
    title: 'ยืนยันลบบทความ!',
    text: 'คุณต้องการลบบทความทั้งหมดที่เลือกใช่หรือไม่?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก'
  })

  if (result.isConfirmed) {
    const token = localStorage.getItem('token')
    if (token) {
      await axiosAPI.post('/api/posts/deleteSelected', { ids: selectedItems.value }, {
        headers: { Authorization: `Bearer ${token}` }
      })
      posts.value = posts.value.filter(post => !selectedItems.value.includes(post.id))
      selectedItems.value = []
    }
  }
}
</script>
