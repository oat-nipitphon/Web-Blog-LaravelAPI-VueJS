<template>
  <div class="bg-white border rounded-lg shadow-md m-2 p-2">
    <div class="grid grid-cols-2">
      <div class="grid grid-cols-2">
        <button
          class="m-auto p-2"
          type="submit"
          @click="storeEventPostPop(postID, authProfileID, 'like')"
        >
          <svg
            v-if="
              postPops.some(
                (pop) =>
                  pop.popProfileID === authProfileID && pop.popStatus === 'like'
              )
            "
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-up-fill text-blue-600 w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <!-- path -->
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-up-fill w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <!-- path -->
          </svg>
        </button>
        <p class="m-auto p-2 text-2xl font-bold">
          {{ postPops.filter((pop) => pop.popStatus === "like").length }}
        </p>
      </div>

      <div class="grid grid-cols-2">
        <button
          class="m-auto p-2"
          type="submit"
          @click="storeEventPostPop(postID, authProfileID, 'disLike')"
        >
          <svg
            v-if="
              postPops.some(
                (pop) =>
                  pop.popProfileID === authProfileID &&
                  pop.popStatus === 'disLike'
              )
            "
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-down-fill text-red-600 w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <!-- path -->
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-down-fill w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <!-- path -->
          </svg>
        </button>
        <p class="m-auto p-2 text-2xl font-bold">
          {{ postPops.filter((pop) => pop.popStatus === "disLike").length }}
        </p>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  postID: {
    type: Number,
    default: null,
  },
  authProfileID: {
    type: Number,
    default: null,
  },
  postPops: {
    type: Array,
    default: () => [], // ป้องกัน undefined เวลา filter/some
  },
  onEventPostPop: {
    type: Function,
    default: () => [],
  },
});
</script>
