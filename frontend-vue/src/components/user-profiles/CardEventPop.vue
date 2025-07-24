<template>
      <div class="flex flex-col items-center">
        <div class="grid grid-rows-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            :class="[
              isPoping
                ? 'text-red-500 size-10 roundend-full p-1'
                : 'text-gray-700 size-10 roundend-full p-1',
            ]"
            @click="props.onPop(props.profile.profileID, profileIDEvent)"
          >
            <path
              d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"
            />
          </svg>
          <span class="text-sm font-semibold text-gray-700">
            {{ countPops }}
          </span>
        </div>
      </div>
</template>
<script setup>
import { ref, computed } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const profileIDEvent = ref(authStore.users.userProfile.id ?? null);

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({
      profileID: null,
      followers: [],
      pops: [],
    }),
  },
  onPop: {
    type: Function,
    required: true,
  },
});
const isPoping = computed(() =>
  props.profile.pops.some(
    (p) =>
      p.profile_id_pop === profileIDEvent.value &&
      (p.status === true || p.status === "true")
  )
);

const countPops = computed(
  () =>
    props.profile.pops.filter((p) => p.status === true || p.status === "true")
      .length
);
</script>