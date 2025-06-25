import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from '@/stores/auth'

import IndexView from "@/views/IndexView.vue";
import HomeView from "@/views/HomeView.vue";

import DashboardProfileView from "@/views/user-profiles/DashboardProfileView.vue";

import CreatePostView from '@/views/posts/CreatePostView.vue'
import EditPostView from "@/views/posts/EditPostView.vue";

import StorePostsView from "@/views/posts/StorePostsView.vue";
import HomeRewardView from "@/views/rewards/HomeRewardView.vue";
import ReportCardItemsView from "@/views/rewards/ReportCardItemsView.vue";
import CreateRewardView from "@/views/rewards/CreateRewardView.vue";
import EditRewardView from "@/views/rewards/EditRewardView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [

    {
      path: "/:pathMatch(.*)*",
      component: () => import("@/views/PageNotFoundView.vue"),
    },
    {
      path: "/",
      name: "IndexView",
      component: IndexView,
      meta: { guest: true }
    },
    {
      path: "/HomeView",
      name: "HomeView",
      component: HomeView,
      meta: { auth: true }
    },

    //------------------- Account -----------------------
    {
      path: '/user_profiles/:id',
      name: 'DashboardProfileView',
      component: DashboardProfileView,
      meta: { auth: true }
    },

    //----------- Post Create Update and Delete -------------
    {
      path: "/CreatePostView",
      name: "CreatePostView",
      component: CreatePostView,
      meta: { auth: true }
    },
    {
      path: '/posts/:id',
      name: 'EditPostView',
      component: EditPostView,
      meta: { auth: true }
    },
    // #Delete post event api success


    //---------------- Post Stores ------------------
    {
      path: '/posts/stores/:profileID',
      name: 'StorePostsView',
      component: StorePostsView,
      meta: { auth: true }
    },
    
    //----------------- Rewards -------------------
    {
      path: '/HomeRewardsView',
      name: 'HomeRewardsView',
      component: HomeRewardView,
      meta: { auth: true }
    },
    {
      path: '/CreateRewardView',
      name: 'CreateRewardView',
      component: CreateRewardView,
      meta: { auth: true }
    },
    {
      path: '/rewards/:id',
      name: 'EditRewardView',
      component: EditRewardView,
      meta: { auth: true }
    },
    {
      path: '/report_card_items/:{profileID}',
      name: 'ReportCardItemsView',
      component: ReportCardItemsView,
      meta: { auth: true }
    },

  ],
});

router.beforeEach(async (to, form) => {
  const authStore = useAuthStore();
  await authStore.storeAuth();
  
  if (!authStore.users && to.meta.auth) {
    return { name: 'IndexView' }
  }

    if (authStore.users && to.meta.guest) {
    return { name: 'HomeView' }
  }

});

export default router;
