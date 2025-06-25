<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfileImage;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $userProfiles = UserProfile::with([
                'profile_image',
                'profile_contacts',
                'profile_pops',
                'profile_followers',
                'posts'
            ])
                ->get()
                ->map(function ($profile) {
                    return $profile;
                });

            if (empty($userProfiles)) {

                return response()->json([
                    'message' => "laravel get user profile false.",
                    'user_profile' => $userProfiles
                ], 404);
            }

            return response()->json([
                'message' => "laravel get user profile success.",
                'user_profile' => $userProfiles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "laravel get user profile function error",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'profile_id' => 'nullabel|integer',
                'title_name' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'nick_name' => 'required|string',
                'birth_day' => 'required|date',
            ]);



            $format_birth_day = Carbon::parse($validated['birth_day'])->format('Y-m-d');

            $user_profile = UserProfile::create([
                'title_name' => $validated['title_name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'nick_name' => $validated['nick_name'],
                'birth_day' => $format_birth_day,
                'created_at' => now(),
            ]);

            if (!$user_profile) {
                return response()->json([
                    'message' => "laravel store user profile where id false",
                    'status' => false
                ], 400);
            }

            return response()->json([
                'message' => 'laravel store user profile success',
                'userProfile' => $user_profile,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'laravel store user profile function error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $user_profile = UserProfile::with([
                'profile_image',
                'profile_contacts',
                'profile_pops',
                'profile_followers',
                'users.user_status',
                'posts'
            ])->findOrFail($id);


            $user_profile = [

                'profile' => $user_profile ? [
                    'id' => $user_profile?->id,
                    'titleName' => $user_profile?->title_name,
                    'firstName' => $user_profile?->first_name,
                    'lastName' => $user_profile?->last_name,
                    'nickName' => $user_profile?->nick_name,
                    'birthDay' => $user_profile?->birth_day,
                    'created_at' => $user_profile?->created_at,
                    'updated_at' => $user_profile?->updated_at,
                ] : null,

                'profileImage' => $user_profile && $user_profile->profile_image ? [
                    'id' => $user_profile->profile_image->id,
                    'profile_id' => $user_profile->profile_image->profile_id,
                    'imageData' => $user_profile->profile_image->image_data,
                ] : null,

                'profileContacts' => $user_profile?->profile_contacts ?
                    $user_profile?->profile_contacts->map(function ($contact) {
                        return [
                            'id' => optional($contact)->id,
                            'name' => optional($contact)->name,
                            'url' => optional($contact)->url,
                            'detail' => optional($contact)->detail,
                            'status' => optional($contact)->status,
                            'imageData' => optional($contact)->image_data,
                            'created_at' => optional($contact)->created_at,
                            'updated_at' => optional($contact)->updated_at,
                        ];
                    }) : null,

                'user' => $user_profile->users ? [
                    'id' => $user_profile->users?->id,
                    'email' => $user_profile->users?->email,
                    'username' => $user_profile->users?->username,
                    'password' => $user_profile->users?->password,

                    'userStatus' => $user_profile->users->user_status &&
                        $user_profile->users->user_status ? [
                            'id' => $user_profile->users->user_status->id,
                            'name' => $user_profile->users->user_status->name,
                            'icon' => optional($user_profile->users->user_status)->icon,
                        ] : null,

                ] : null,

                'posts' => $user_profile->posts ?
                    $user_profile->posts->map(function ($post) {
                        return [
                            'id' => optional($post)->id,
                            'title' => optional($post)->title,
                            'content' => optional($post)->content,
                            'refer' =>  optional($post)->refer,
                            'status' =>  optional($post)->status,
                            'created_at' =>  optional($post)->created_at,
                            'updated_at' =>  optional($post)->updated_at,

                            'postImage' => optional($post)->post_images ?
                                optional($post)->post_images->map(function ($image) {
                                    return [
                                        'id' => $image?->id,
                                        'imageData' => optional(base64_encode($image->image_data)),
                                    ];
                                }) : null,
                        ];
                    }) : null,

            ];


            if (!empty($user_profile)) {
                return response()->json([
                    'message' => "UserProfileController show() success.",
                    'userProfile' => $user_profile
                ], 200);
            }

            return response()->json([
                'message' => "UserProfileController show() where id false.",
                'userProfile' => $user_profile
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "UserProfileController function error",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'profile_id' => 'required|integer',
                'title_name' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'nick_name' => 'required|string',
                'birth_day' => 'required|date',
            ]);

            $user_profile = UserProfile::findOrFail($request->profile_id);

            // format birth day
            // $format_birth_day = Carbon::parse($validated['birth_day'])->format('Y-m-d');

            if (!$user_profile) {
                return response()->json([
                    'message' => "controller user profile update",
                    'id' => $id,
                ], 400);
            }

            $user_profile->update(
                [
                    'title_name' => $validated['title_name'],
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'nick_name' => $validated['nick_name'],
                    'birth_day' => $validated['birth_day'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'message' => 'controller user profile update',
                'userProfile' => $user_profile
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "laravel update user profile function error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function uploadImageProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'profile_id' => 'required|integer',
                'image_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            if ($request->hasFile('image_file')) {


                $image_file = $request->file('image_file');
                $image_data = file_get_contents($image_file->getRealPath());
                $image_data_base64 = base64_encode($image_data);

                UserProfileImage::updateOrCreate(
                    ['profile_id' => $validated['profile_id']],
                    [
                        'image_data' => $image_data_base64,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                return response()->json([
                    'message' => 'controller profile image success',
                    'profileImage' => $image_data_base64
                ], 201);
            }

            return response()->json([
                'message' => 'controller image file not found',
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'upload image profile () function error',
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
