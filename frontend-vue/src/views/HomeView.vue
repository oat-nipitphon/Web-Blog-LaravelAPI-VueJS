<template>
  <div
    v-if="storePosts"
    class="grid max-w-screen-xl mx-auto px-4 grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 m-2"
  >
    <div
      class="w-full p-2 rounded-lg shadow-lg transition hover:shadow-lg m-auto dark:bg-gray-800 dark:border-gray-700"
      v-for="post in storePosts"
      :key="post.id"
    >
      <ProfileCard
        :user="post?.user"
        :profile="post?.userProfile"
      />
      <CardReportPost :post="post" />
    </div>
  </div>
  <div v-else class="m-auto p-3 bg-red-500 flex justify-center">
    <p class="text-3xl font-bold text-white">Post require false !!</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { usePostStore } from "@/stores/post";
import { useStoreUserProfile } from "@/stores/user-profile";

import ProfileCard from "@/components/user-profiles/ProfileCard.vue";
import CardReportPost from "@/components/posts/CardReportPost.vue";

const router = useRouter();

const authStore = useAuthStore();
const { users } = storeToRefs(authStore);
const authProfileID = ref(users.value?.userProfile?.id || null);
const profileIDLogin = authProfileID.value;

const postStore = usePostStore();
const { storePosts } = storeToRefs(postStore);

const { storeProfileFollowers, storeProfilePop } = useStoreUserProfile();

// ✅ ติดตาม / ยกเลิกติดตาม แล้ว update profile ทันที
const onFollowersProfile = async (profileID) => {
  const success = await storeProfileFollowers(profileID, authProfileID.value);
  if (success) {
    console.log("Followed/unfollowed successfully");
    const post = storePosts.value.find(
      (p) => p.userProfile?.profileID === profileID
    );
    if (post) {
      post.userProfile.followers = success.followers; // update followers
    }
  }
};

// ✅ กด Pop/Unpop แล้ว update profile ทันที
const onPopProfile = async (profileID) => {
  const success = await storeProfilePop(profileID, authProfileID.value);
  if (success) {
    console.log("Popped/unpopped successfully");
    const post = storePosts.value.find(
      (p) => p.userProfile?.profileID === profileID
    );
    if (post) {
      post.userProfile.pops = success.pops; // update pops
    }
  }
};

onMounted(async () => {
  await postStore.storeGetPosts();
});
</script>
