<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserWallet;
use App\Models\User;
use App\Models\Post;
use App\Models\Reward;


class ManagerBlogController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // Get UserProfiles Manager-ReportUserProfileView
    public function managerGetUserProfiles()
    {
        try {

            $user_profiles = User::with([
                'user_status',
                'user_wallet',
                'report_log_logins',
                'user_profile.profile_image',

            ])->get();

            if (!$user_profiles) {
                return response()->json([
                    'message' => 'managerGetUserProfiles() false',
                ], 400);
            }

            return response()->json([
                'message' => 'managerGetUserProfiles() success',
                'userProfiles' => $user_profiles
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "managerGetUserProfiles() error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    // Get UserProfiles Manager-ReportUserProfileView
    public function managerGetPosts()
    {
        try {

            $posts = Post::with([
                'post_type',
                'post_images',
                'post_pops',
                'user_profiles'
            ])->get();

            if (!$posts) {
                return response()->json([
                    'message' => 'managerGetPosts() false',
                ], 400);
            }

            return response()->json([
                'message' => 'managerGetPosts() success',
                'posts' => $posts
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "managerGetPosts() error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    // Get UserProfiles Manager-ReportUserProfileView
    public function managerGetRewards()
    {
        try {

            $rewards = Post::with([
                'reward_status',
                'reward_images'
            ])->get();

            if (!$rewards) {
                return response()->json([
                    'message' => 'managerGetRewards() false',
                ], 400);
            }

            return response()->json([
                'message' => 'managerGetRewards() success',
                'rewards' => $rewards
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "managerGetRewards() error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    // Get UserProfiles Manager-ReportUserProfileView
    public function managerGetWellets()
    {
        try {
        } catch (\Exception $error) {
            return response()->json([
                'message' => "managerGetWellets() error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
