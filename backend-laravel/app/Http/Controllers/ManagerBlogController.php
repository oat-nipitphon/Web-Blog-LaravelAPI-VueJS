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

            $users = User::with([

                'user_status',
                'check_status_login',

                'user_profile',
                'user_profile.profile_image',
                'user_profile.profile_contacts',
                'user_profile.profile_pops',
                'user_profile.profile_followers',

            ])->get()->map(function ($user) {
                return $user ? [

                    'users' => $user ? [
                        'id' => optional($user)->id,
                        'name' => optional($user)->name,
                        'email' => optional($user)->email,
                        'username' => optional($user)->username,
                        'statusID' => optional($user)->status_id,
                        'statusAccount' => optional($user)->status_account,

                        'status' => $user->user_status ? [
                            'id' => optional($user->user_status)->id,
                            'name' => optional($user->user_status)->name,
                        ] : null,

                        'checkStatusOnline' => $user->check_status_login ? [
                            'id' => optional($user->check_status_login)->id,
                            'status' => optional($user->check_status_login)->status,
                            'timeIn' => optional($user->check_status_login)->time_in,
                            'timeOut' => optional($user->check_status_login)->time_out,
                            'totalTimeLogin' => optional($user->check_status_login)->time_total_login,
                        ] : null,


                    ] : null,

                    'profiles' => $user ? [

                        'id' => optional($user->user_profile)->id,
                        'titleName' => optional($user->user_profile)->title_name,
                        'firstName' => optional($user->user_profile)->first_name,
                        'lastName' => optional($user->user_profile)->last_name,
                        'nickName' => optional($user->user_profile)->nick_name,
                        'birthDay' => optional($user->user_profile)->birth_day,

                        'fullName' => optional($user->user_profile)->title_name .
                            ' ' . optional($user->user_profile)->first_name .
                            ' ' . optional($user->user_profile)->last_name,

                        'image' => $user->user_profile->profile_image ? [
                            'id' => optional($user->user_profile->profile_image)->id,
                            'profileID' => optional($user->user_profile->profile_image)->profile_id,
                            'imageData' => optional($user->user_profile->profile_image)->image_data,
                        ] : null,

                        'contacts' => $user->user_profile->profile_contacts ?
                            $user->user_profile->profile_contacts->map(function ($contact) {
                                return [
                                    'id' => optional($contact)->id,
                                    'name' => optional($contact)->name,
                                    'url' => optional($contact)->url,
                                    'detail' => optional($contact)->detail,
                                    'status' => optional($contact)->status,
                                    'imageData' => base64_encode(optional($contact)->image_data),
                                ];
                            }) : null,

                        'pops' => $user->user_profile->profile_pops ?
                            $user->user_profile->profile_pops->map(function ($pop) {
                                return [
                                    'profileID' => optional($pop)->profile_id,
                                    'popID' => optional($pop)->profile_id_pop,
                                    'status' => optional($pop)->status, // like , disLike
                                ];
                            }) : null,

                        'followers' => $user->user_profile->profile_followers ?
                            $user->user_profile->profile_followers->map(function ($follower) {
                                return [
                                    'profileID' => optional($follower)->profile_id,
                                    'followerID' => optional($follower)->profile_id_followers,
                                    'status' => optional($follower)->status, // true , false
                                ];
                            }) : null,

                    ] : null,

                ] : null;
            });

            if (!$users) {
                return response()->json([
                    'message' => 'managerGetUserProfiles() false',
                ], 400);
            }

            return response()->json([
                'message' => 'managerGetUserProfiles() success',
                'userProfiles' => $users
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
            ])->get()->map(function ($post) {
                return $post ? [

                    'id' => optional($post)->id,
                    'title' => optional($post)->title,
                    'content' => optional($post)->content,
                    'refer' => optional($post)->refer,
                    'createdAt' => optional($post)->created_at,
                    'updatedAt' => optional($post)->updated_at,

                    'type' => $post->post_type ? [
                        'id' => optional($post->post_type)->id,
                        'name' => optional($post->post_type)->name,
                    ] : null,

                    'status' => $post->post_status ? [
                        'id' => optional($post->post_status)->id,
                        'name' => optional($post->post_status)->name,
                    ] : null,

                    'pops' => $post->post_pops ?
                        $post->post_pops->map(function ($pop) {
                            return [
                                'post_id' => optional($pop)->post_id,
                                'profile_id' => optional($pop)->profile_id,
                                'status' => optional($pop)->status,
                            ];
                        }) : null,

                ] : null;
            });

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
            ])->get()->map(function ($reward) {
                return $reward ? [

                    'id' => optional($reward)->id,
                    'name' => optional($reward)->name,
                    'point' => optional($reward)->point,
                    'amount' => optional($reward)->amount,
                    'createdAt' => optional($reward)->created_at,
                    'updatedAt' => optional($reward)->updated_at,

                    'status_id' => [
                        'id' => optional($reward->reward_status)->id,
                        'name' => optional($reward->reward_status)->name,
                    ],

                    'image' => $reward->reward_images ?
                        $reward->reward_images->map(function ($image) {
                            return [
                                'id' => optional($image)->id,
                                'imageData' => optional($image)->image_data,
                            ];
                        }) : null,

                ] : null;
            });

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
