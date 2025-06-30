<template>
<div class="grid grid-cols-2 bg-white rounded-lg shadow-lg">
  <div class="w-50 flex justify-center items-center">
    <RouterLink class="block border border-blue-600 bg-blue-600 px-12 py-3 transition-transform group-hover:-translate-x-1 group-hover:-translate-y-1"
      :to="{ name: 'ManagerReportRewardView' }"
    >
      Reward
    </RouterLink>
  </div>
  <div class="w-50"></div>
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