<!-- <script setup>
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useRewardCartStore } from '@/stores/reward.cart'

const defaultImage =
  'https://png.pngitem.com/pimgs/s/104-1042351_gifto-gto-icon-gifto-token-hd-png-download.png'

const props = defineProps({
  rewards: Object,
})

const rewardCartStore = useRewardCartStore()

const addToCart = (reward) => {
  rewardCartStore.addToCart(reward)
}

const resetCart = () => {
  rewardCartStore.resetCart()
}

</script>
<template>
  <div class="bg-white">
    <div
      class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
    >
      <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-10">

        <div v-for="reward in props.rewards" :key="reward.id" class="m-3">
          <div v-if="reward.rewardImage && reward.rewardImage.length > 0">
            <div
              v-for="image in reward.rewardImage"
              :key="image.id"
              class="group relative"
            >
              <img
                :src="image.image_data ? image.image_data : defaultImage"
                class="w-full h-40 sm:h-25 md:h-32 lg:h-44 xl:h-52 rounded-md bg-gray-200 object-cover group-hover:opacity-75"
              />
            </div>
          </div>
          <div v-else>
            <img
              :src="defaultImage"
              class="w-full h-40 sm:h-25 md:h-32 lg:h-44 xl:h-52 rounded-md bg-gray-200 object-cover group-hover:opacity-75"
            />
          </div>
          <div class="mt-4 grid grid-cols-2">
            <div class="grid grid-rows-2">
              <h3 class="text-sm text-gray-700 mt-auto mb-auto">
                <span class="text-2xl">
                  {{ reward.name }}
                </span>
              </h3>
              <span class="text-1xl font-normal mt-auto">
                คงเหลือ {{ reward.amount }}
              </span>
            </div>
            <div class="gird grid-rows-2 text-right">
              <p class="text-2xl font-medium text-gray-900 mt-2">
                {{ reward.point }} point
              </p>
              <button class="btn btn-sm btn-outline-primary"
                @click="addToCart(reward)"
              >
                  <span class="font-blod text-md">
                    Add +
                  </span>
                </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template> -->