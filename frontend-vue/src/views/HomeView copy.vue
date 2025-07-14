<template>
  <div
    v-if="storePosts"
    class="grid max-w-screen-xl mx-auto px-4 grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 m-2"
  >
    <div
      class="w-max-full p-2 rounded-lg shadow-lg transition hover:shadow-lg m-auto dark:bg-gray-800 dark:border-gray-700"
      v-for="post in storePosts"
      :key="post.id"
    >
      <ProfileCard
        :user="post?.user"
        :profile="post?.userProfile"
        :auth-profile-id="profileIDLogin"
        :on-followers-profile="onFollowersProfile"
        :on-pop-profile="onPopProfile"
        :on-check-profile-status="onCheckProfileStatus(post?.userProfile)"
      />
      <CardReportPost :post="post" />
    </div>
  </div>
  <div v-else class="m-auto p-3 bg-red-500 flex justify-center">
    <p class="text-3xl font-blod text-white">Post require false !!</p>
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import { useStoreUserProfile } from "@/stores/user-profile";

import ProfileCard from "@/components/user-profiles/ProfileCard.vue";
import CardReportPost from "@/components/posts/CardReportPost.vue";

const router = useRouter();

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);

const authProfileID = ref(null);
authProfileID.value = authStore?.users?.userProfile?.id;

const postStore = usePostStore();
const { storePosts } = storeToRefs(postStore);

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();

const onFollowersProfile = async (profileID, authProfileID) => {
  const success = await storeProfileFollowers(profileID, authProfileID);
  if (success) {
    return;
  }
  return;
};

const onPopProfile = async (profileID, authProfileID) => {
  const success = await storeProfilePop(profileID, authProfileID);
  if (success) {
    return;
  }
  return;
};

const onCheckProfileStatus = computed(() => {
  return (profile) => ({
    isFollower:
      profile?.followers?.some(
        (f) =>
          f?.profile_id_followers === authProfileID &&
          (f.status === true || f.status === "true")
      ) || false,

    isFollowersCount: profile?.followers?.filter(
      (row) => row.status === "true" || row.status === true
    ).length,

    isPop:
      profile?.pops?.some(
        (p) =>
          p?.profile_id_pop === authProfileID &&
          (p.status === true || p.status === "true")
      ) || false,

    isPopsCount: profile?.pops?.filter(
      (row) => row.status === "true" || row.status === true
    ).length,
  });
  postStore.storeGetPosts()
});


onMounted(async () => {
  await postStore.storeGetPosts();
});
</script>
