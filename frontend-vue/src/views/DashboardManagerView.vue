<template>
  <div class="w-max-md flex justify-between rounded-md shadow-lg bg-white">
    <div class="grid grid-cols-2">
      <div class="flex-1/4 border-gray-900 p-3 font-normal text-2xl">
        <RouterLink
          :to="{
            name: 'CreateRewardView'
          }"
        ></RouterLink>
      </div>
      <div class="flex-1/4 border-gray-900 p-3 font-normal text-2xl">
        
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckIcon } from '@heroicons/vue/20/solid'
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
console.log('dashboard manager view ', authStore?.users?.username);


const tiers = [
  {
    name: 'Hobby',
    id: 'tier-hobby',
    href: '#',
    priceMonthly: '$29',
    description: "The perfect plan if you're just getting started with our product.",
    features: ['25 products', 'Up to 10,000 subscribers', 'Advanced analytics', '24-hour support response time'],
    featured: false,
  },
  {
    name: 'Enterprise',
    id: 'tier-enterprise',
    href: '#',
    priceMonthly: '$99',
    description: 'Dedicated support and infrastructure for your company.',
    features: [
      'Unlimited products',
      'Unlimited subscribers',
      'Advanced analytics',
      'Dedicated support representative',
      'Marketing automations',
      'Custom integrations',
    ],
    featured: true,
  },
]
</script>