<template>
  <div class="flex justify-center">
    <button
      class="btn btn-sm"
      @click="
        props.onPopProfile(props?.profile?.profileID, props?.profileIDFollowers)
      "
    >
      <div class="grid grid-cols-3">
        <div class="flex justify-center">
          <svg
            v-if="
              props?.profile?.pops.some(
                (row) =>
                  row.profile_id_pops === props?.profileIDPops &&
                  (row.status === 'true' || row.status === true)
              )
            "
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-heart-fill text-red-600 w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              fill-rule="evenodd"
              d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
            />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-heart-fill w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              fill-rule="evenodd"
              d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"
            />
          </svg>
        </div>
        <div class="flex justify-center">
          <p class="m-auto mt-auto text-gray-700 text-md-2xl">
            {{
              props?.profile?.pops.filter(
                (row) => row.status === "true" || row.status === true
              ).length
            }}
          </p>
        </div>
      </div>
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  profile: {
    type: Object,
    default: () => ({}),
  },
  profileIDFollowers: {
    type: Number,
    default: null,
  },
  onFollowersProfile: {
    type: Function,
    default: true,
  },
});
</script>
