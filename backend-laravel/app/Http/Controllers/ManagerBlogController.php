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

    public function managerUpdateStatusAccount(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required|string',
                'status' => 'required|string'
            ]);

            $user = User::findOrFail($request->id);

            if (!$user) {
                return response()->json([
                    'message' => 'where user id false',
                    'userID' => $request->id
                ]);
            }

            $user->update(
                ['status_account' => $request->status]
            );

            return response()->json([
                'message' => 'update status account success',
                'statusAccount' => $user->status_account,
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'controller update status account () error',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    // Get Posts Manager-ReportPostView
    public function managerGetPosts()
    {
        try {

$posts = Post::with([
                'post_type',
                'post_images',
                'post_pops',
                'user_profiles.users.user_status',
                'user_profiles.profile_image',
                'user_profiles.profile_pops',
                'user_profiles.profile_followers',
            ])->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($post) {
                    return [

                        'postID' => optional($post)->id,
                        'postTitle' => optional($post)->title,
                        'postContent' => optional($post)->content,
                        'postRefer' => optional($post)->refer,
                        'postStatus' => optional($post)->status,
                        'postCreatedAT' => optional($post)->created_at,
                        'postUpdatedAT' => optional($post)->updated_at,


                        'postType' => [
                            'typeID' => optional($post->post_type)->id,
                            'typeName' => optional($post->post_type)->name,
                        ],

                        'postImage' => $post->post_images->map(fn($image) => [
                            'imageID' => optional($image)->id,
                            'imageData' => optional($image)->image_data
                        ]),

                        'postPops' => $post->post_pops->map(fn($pop) => [
                            'popID' => optional($pop)->id,
                            'popPostID' => optional($pop)->post_id,
                            'popProfileID' => optional($pop)->profile_id,
                            'popStatus' => optional($pop)->status
                        ]),

                        'userProfile' => [
                            'profileID' => optional($post->user_profiles)->id,
                            'titleName' => optional($post->user_profiles)->title_name,
                            'firstName' => optional($post->user_profiles)->first_name,
                            'lastName' => optional($post->user_profiles)->last_name,
                            'nickName' => optional($post->user_profiles)->nick_name,
                            'birthDay' => optional($post->user_profiles)->birth_day,

                            'image' => $post->user_profiles->profile_image ? [
                                'id' => optional($post->user_profiles->profile_image)->id,
                                'imageData' => optional($post->user_profiles->profile_image)->image_data,
                            ] : null,

                            'followers' => $post->user_profiles->profile_followers ?
                                $post->user_profiles->profile_followers->map(fn($row) => [
                                    'id' => optional($row)->id,
                                    'profile_id' => optional($row)->profile_id,
                                    'profile_id_followers' => optional($row)->profile_id_followers,
                                    'status' => optional($row)->status,
                                ]) : null,

                            'pops' => $post->user_profiles->profile_pops ?
                                $post->user_profiles->profile_pops->map(fn($row) => [
                                    'id' => optional($row)->id,
                                    'profile_id' => optional($row)->profile_id,
                                    'profile_id_pop' => optional($row)->profile_id_pop,
                                    'status' => optional($row)->status,
                                ]) : null,

                        ],

                        'user' => [
                            'userID' => optional($post->user_profiles->users)->id,
                            'username' => optional($post->user_profiles->users)->username,
                            'name' => optional($post->user_profiles->users)->name,
                            'email' => optional($post->user_profiles->users)->email,

                            'userStatus' => [
                                'statusID' => optional($post->user_profiles->users->user_status)->id,
                                'statusName' => optional($post->user_profiles->users->user_status)->name,
                            ],
                        ]
                    ];
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

    // Get Rewards Manager-ReportRewardView
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
