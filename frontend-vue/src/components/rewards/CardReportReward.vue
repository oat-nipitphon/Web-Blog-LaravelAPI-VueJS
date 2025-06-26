<script setup>
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
 
</template>