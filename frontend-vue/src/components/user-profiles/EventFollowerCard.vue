<template>
  <div class="flex justify-center">
    <button
      class="btn btn-sm"
      @click="
        props.onFollowersProfile(props?.profile?.profileID, props?.profileIDFollowers)
      "
    >
      <div class="grid grid-cols-3">
        <!-- Icon -->
        <div class="flex justify-center">
          <svg
            v-if="
              props?.profile?.followers.some(
                (row) =>
                  row.profile_id_followers === props?.profileIDFollowers &&
                  (row.status === 'true' || row.status === true)
              )
            "
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-bell-fill text-yellow-300 w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
            />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-bell-fill w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"
            />
          </svg>
        </div>
        <div class="flex justify-center">
          <p class="m-auto mt-auto text-gray-700 text-md-2xl">
            {{
              props?.profile?.followers.filter(
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
