<!-- <script setup>
import { onMounted, ref, reactive, computed } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useAdminPostStore } from "@/stores/admin.posts";

const authStore = useAuthStore();

const items = ref(Array.from({ length: 100 }, (_, i) => `Item ${i + 1}`)); // ตัวอย่างข้อมูล 100 รายการ
const { adminAPIGETposts, adminAPIPostBlockOrUnBlock, adminAPIPostDelete } =
  useAdminPostStore();

const posts = ref([]);
const modalPostID = ref([]);
const modalPostContent = ref([]);

const modalUserProfileID = ref([]);
const modalUserProfileFullName = ref([]);
const modalUserProfileFollowers = ref([]);

const currentPage = ref(1); 
const itemsPerPage = ref(20);


const totalPages = computed(() =>
  Math.ceil(posts.value.length / itemsPerPage.value)
);


const paginatedPosts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return posts.value.slice(start, end);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const blockOrUnBlock = async (postID, blockStatus) => {
  console.log("postID :: ", postID);
  console.log("blockStatus :: ", blockStatus);
};

const modalValuePostContent = (post) => {
  modalPostID.value = post.id;
  modalPostContent.value = post.post_content;
};

const modalValueUserProfile = (userProfile) => {
  console.log("user profile", userProfile);
  modalUserProfileID.value = userProfile.id;
  modalUserProfileFullName.value = userProfile.full_name;
};

onMounted(async () => {
  posts.value = await adminAPIGETposts();
});
</script>
<template>
  <div>
    <div class="w-full">
      <div class="grid grid-cols-2 mb-4">
        <div class="flex justify-start">
          <h2 class="text-gray-900 mt-4 mb-4 p-2">Admin Manager Posts</h2>
        </div>
        <div class="flex justify-end">

        </div>
      </div>

      <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table
          class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
        >
          <thead
            class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400"
          >
            <tr>
              <th class="w-2 p-3 text-center font-semibold">Post ID</th>
              <th class="w-4 p-3 text-center font-semibold">Detail Post</th>
              <th class="w-3 p-3 text-center font-semibold">Status Post</th>
              <th class="w-4 p-3 text-center font-semibold">
                User Create Post
              </th>
              <th class="w-5 p-3 text-center font-semibold">Events</th>
            </tr>
          </thead>
          <tbody v-if="paginatedPosts.length > 0">
            <tr
              v-for="(post, index) in paginatedPosts"
              :key="index"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
            >
              <td class="py-2 text-center">{{ post.id }}</td>
              <td class="p-3">
                <button
                  @click="modalValuePostContent(post)"
                  type="button"
                  class="text-blue-500 hover:text-blue-700"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModalDetailPost"
                >
                  {{ post.post_title }}
                </button>
              </td>
              <td class="text-center">
                <p
                  v-if="
                    post.deletetion_status === 'false' &&
                    post.block_status === 'false'
                  "
                  class="text-green-500 text-sm"
                >
                  Normal
                </p>
                <p
                  v-if="post.deletetion_status === 'true'"
                  class="text-red-600 text-sm"
                >
                  Deletetion
                </p>
                <p
                  v-if="post.block_status === 'true'"
                  class="text-red-600 text-sm"
                >
                  ** Block Post **
                </p>
              </td>
              <td class="text-center">
                <button
                  @click="modalValueUserProfile(post.user.user_profile)"
                  type="button"
                  class="text-blue-500 hover:text-blue-700"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModalUserProfile"
                >
                  {{ post.user.user_profile.full_name }}
                </button>
              </td>
              <td class="text-center">
                <div class="dropdown m-auto">
                  <button
                    class="dropdown-toggle btn btn-sm btn-event mt-2"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    Event
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <button
                        v-if="post.block_status === 'false'"
                        @click="adminAPIPostBlockOrUnBlock(post.id, 'Block')"
                        class="btn btn-block dropdown-item m-2"
                      >
                        🔒 Block
                      </button>
                      <button
                        v-if="post.block_status === 'true'"
                        @click="adminAPIPostBlockOrUnBlock(post.id, 'Unblock')"
                        class="btn btn-unblock dropdown-item m-2"
                      >
                        ✅ Unblock
                      </button>
                    </li>
                    <li>
                      <button
                        @click="adminAPIPostDelete(post.id)"
                        class="btn btn-delete dropdown-item m-2"
                      >
                        Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </tbody>

          <tbody v-else>
            <tr>
              <td colspan="5" class="text-center text-lg text-red-600 py-4">
                Data posts not available.
              </td>
            </tr>
          </tbody>
        </table>


        <div class="flex justify-between items-center mt-4">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="btn btn-primary"
          >
            Previous
          </button>
          <span class="text-lg"
            >Page {{ currentPage }} of {{ totalPages }}</span
          >
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="btn btn-primary"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="exampleModalDetailPost"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Post ID {{ modalPostID }}</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body p-3">

             <p v-html="modalPostContent"
              class="text-1xl font-normal"
             ></p>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="exampleModalUserProfile"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              User Profile ID {{ modalUserProfileID }}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            {{ modalUserProfileFullName }}
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>

.btn-event {
  background-color: #3498db;
  border-radius: 5px;
}

.dropdown-menu {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 8px 0;
}

.dropdown-item {
  padding: 8px 12px;
  border-radius: 5px;
  transition: background 0.3s ease;
}

.dropdown-item:hover {
  background-color: #f1f1f1;
}

.btn-block {
  background-color: #e74c3c;
  color: white;
}

.btn-unblock {
  background-color: #27ae60; 
  color: white;
}

.btn-delete {
  background-color: #e74c3c;
  color: white;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.text-blue-500 {
  color: #3498db;
}
</style> -->
