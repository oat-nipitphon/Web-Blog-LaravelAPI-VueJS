<!-- <script setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import axiosAPI from '@/services/axiosAPI'
import { useAuthStore } from '@/stores/auth'
import { useRewardCartStore } from '@/stores/reward.cart'
import { MagnifyingGlassIcon, ShoppingBagIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  counterItems: Object,
  userID: Number,
})

const authStore = useAuthStore()
const { storeUser } = storeToRefs(authStore)
const userPoint = ref(storeUser.value?.user_login?.userPoint?.point || 0)

const rewardCartStore = useRewardCartStore()
const { counterItems, cartItemCounters, totalPoint } = storeToRefs(rewardCartStore)
const itemsCardReset = () => {
  rewardCartStore.resetCart()
}

const form = ref({
  userID: props.userID,
  userAmount: '',
  counterItems: counterItems,
})


const onUserAmount = computed(() => {
  return userPoint.value - totalPoint.value
})

const onSave = async () => {
  const formData = new FormData()
  formData.append('userID', storeUser.value?.user_login?.id)
  formData.append('userAmount', onUserAmount.value)
  formData.append('counterItems', JSON.stringify(counterItems.value))

  try {

    const res = await axiosAPI.post(`/api/cartItems/userConfirmSelectReward`, formData, {
      headers: {
        authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (res.status === 201) {
      window.location.reload()
    } else {
      console.log('confirm selected reward false', res.data);
    }

  } catch (error) {
    console.error('Request failed:', error)
  }
}

</script>
<template>
  <div>
    <button type="button" class="btn btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <ShoppingBagIcon class="w-7 h-7 text-gray-500" />
    </button>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ของรางวัลที่คุณเลือก</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
          </div>
          <div class="modal-body" v-if="props.counterItems">
            <div class="w-max-full">
              <div v-for="(item, index) in props.counterItems" :key="index" class="mb-2">
                <p>ไอดี: {{ index }}</p>
                <p>ชื่อ: {{ item.rewardName }}</p>
                <p>จำนวน: {{ item.rewardAmount }}</p>
                <p>รวม: {{ item.rewardAmount * item.rewardPoint }}</p>
                <hr class="my-2 border-white" />
              </div>
            </div>
          </div>
          <div class="p-3">
            <div class="grid grid-rows-2">
              <div class="grid grid-cols-3 bg-yellow-100  border-b-2">
                <div class="font-normal text-gray-900 text-1xl m-auto p-2">จำนวนรางวัล: {{ cartItemCounters }}</div>
                <div class="font-normal text-gray-900 text-1xl m-auto p-2">จำนวนแต้ม: {{ totalPoint }}</div>
                <div class="font-normal text-gray-900 text-1xl m-auto p-2">แต้มคงเหลือ: {{ onUserAmount }}</div>
              </div>
              <div class="flex justify-end p-2">
                <button type="button" class="btn btn-sm btn-primary m-2" @click="onSave">แลกรางวัล</button>

                <button type="button" class="btn btn-sm btn-outline-danger m-2" @click="itemsCardReset">
                  รีเช็ต
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template> -->
