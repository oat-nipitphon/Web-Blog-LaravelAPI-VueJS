<template>
  <div class="w-max-full">
    <div class="grid grid-cols-2">
      <!-- 👍 Like -->
      <div class="grid grid-cols-2">
        <button class="m-auto p-2" type="submit" @click="handleLike">
          <svg
            v-if="checkStatus.like === 'like'"
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-up-fill text-blue-600 w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"
            />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-up-fill w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"
            />
          </svg>
        </button>
        <p class="m-auto p-2 text-2xl font-bold">
          {{ likeCount }}
        </p>
      </div>

      <!-- 👎 Dislike -->
      <div class="grid grid-cols-2">
        <button class="m-auto p-2" type="submit" @click="handleDislike">
          <svg
            v-if="checkStatus.disLike === 'disLike'"
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-down-fill text-red-600 w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"
            />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            class="bi bi-hand-thumbs-down-fill w-[30px] h-[30px]"
            viewBox="0 0 16 16"
          >
            <path
              d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"
            />
          </svg>
        </button>
        <p class="m-auto p-2 text-2xl font-bold">
          {{ dislikeCount }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed, watch } from "vue";

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  authProfileID: {
    type: Number,
    required: true,
  },
  onEventPostPop: {
    type: Function,
    required: true,
  },
});

const checkStatus = reactive({
  like: "",
  disLike: "",
});

const updateStatus = () => {
  const userPop = props.post.postPops.find(
    (pop) => pop.popProfileID === props.authProfileID
  );
  checkStatus.like = userPop?.popStatus === "like" ? "like" : "";
  checkStatus.disLike = userPop?.popStatus === "disLike" ? "disLike" : "";
};

watch(() => props.post.postPops, updateStatus, { immediate: true });

const handleLike = async () => {
  const status = checkStatus.like === "like" ? null : "like";

  await props.onEventPostPop(props.post.postID, props.authProfileID, status);

  updatePostPops(status);
};

const handleDislike = async () => {
  const status = checkStatus.disLike === "disLike" ? null : "disLike";

  await props.onEventPostPop(props.post.postID, props.authProfileID, status);

  updatePostPops(status);
};


const updatePostPops = (newStatus) => {

  props.post.postPops = props.post.postPops.filter(
    (pop) => pop.popProfileID !== props.authProfileID
  );

  if (newStatus) {
    props.post.postPops.push({
      popProfileID: props.authProfileID,
      popStatus: newStatus,
    });
  }

  updateStatus();
};

const likeCount = computed(
  () => props.post.postPops.filter(
    (pop) => pop.popStatus === "like"
  ).length
);

const dislikeCount = computed(
  () => props.post.postPops.filter((pop) => pop.popStatus === "disLike").length
);
</script>
