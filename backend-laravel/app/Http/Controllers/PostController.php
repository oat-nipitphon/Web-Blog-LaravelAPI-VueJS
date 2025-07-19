<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\UserProfile;
use App\Models\UserWallet;
use App\Models\Post;
use App\Models\PostType;
use App\Models\PostImage;
use App\Models\PostDeletetion;
use App\Models\PostPop;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    // Bonus: Helper null-safe function
    function safe($object, $default = null)
    {
        return $object ?? $default;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
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
            ])
                ->where('status', 'active')
                ->whereHas('user_profiles.users.user_status', function ($query) {
                    $query->whereIn('name', ['admin', 'user']);
                })
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($post) {
                    return [
                        'postID' => safe($post->id),
                        'postTitle' => safe($post->title),
                        'postContent' => safe($post->content),
                        'postRefer' => safe($post->refer),
                        'postStatus' => safe($post->status),
                        'postCreatedAT' => safe($post->created_at),
                        'postUpdatedAT' => safe($post->updated_at),

                        'postType' => safe($post->post_type) ? [
                            'typeID' => safe($post->post_type->id),
                            'typeName' => safe($post->post_type->name),
                        ] : null,

                        'postImage' => $post->post_images->map(fn($image) => [
                            'imageID' => safe($image->id),
                            'imageData' => safe($image->image_data),
                        ]),

                        'postPops' => $post->post_pops->map(fn($pop) => [
                            'popID' => safe($pop->id),
                            'popPostID' => safe($pop->post_id),
                            'popProfileID' => safe($pop->profile_id),
                            'popStatus' => safe($pop->status),
                        ]),

                        'userProfile' => safe($post->user_profiles) ? [
                            'profileID' => safe($post->user_profiles->id),
                            'titleName' => safe($post->user_profiles->title_name),
                            'firstName' => safe($post->user_profiles->first_name),
                            'lastName' => safe($post->user_profiles->last_name),
                            'nickName' => safe($post->user_profiles->nick_name),
                            'birthDay' => safe($post->user_profiles->birth_day),

                            'image' => safe($post->user_profiles->profile_image) ? [
                                'id' => safe($post->user_profiles->profile_image->id),
                                'imageData' => safe($post->user_profiles->profile_image->image_data),
                            ] : null,

                            'followers' => $post->user_profiles->profile_followers->map(fn($row) => [
                                'id' => safe($row->id),
                                'profile_id' => safe($row->profile_id),
                                'profile_id_followers' => safe($row->profile_id_followers),
                                'status' => safe($row->status),
                            ]),

                            'pops' => $post->user_profiles->profile_pops->map(fn($row) => [
                                'id' => safe($row->id),
                                'profile_id' => safe($row->profile_id),
                                'profile_id_pop' => safe($row->profile_id_pop),
                                'status' => safe($row->status),
                            ]),
                        ] : null,

                        'user' => safe($post->user_profiles->users) ? [
                            'userID' => safe($post->user_profiles->users->id),
                            'username' => safe($post->user_profiles->users->username),
                            'name' => safe($post->user_profiles->users->name),
                            'email' => safe($post->user_profiles->users->email),

                            'userStatus' => safe($post->user_profiles->users->user_status) ? [
                                'statusID' => safe($post->user_profiles->users->user_status->id),
                                'statusName' => safe($post->user_profiles->users->user_status->name),
                            ] : null,
                        ] : null,
                    ];
                });

            if (!$posts) {
                return response()->json([
                    'message' => "api controller post function index require false",
                ], 500);
            }
            return response()->json([
                'message' => "api controller post function index require success.",
                'posts' => $posts,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "api post controller function  error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * Function Create Post
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'profile_id' => 'required|integer',
                'type_id' => 'nullable|integer',
                'new_type' => 'nullable|string',
                'title' => 'required|string',
                'content' => 'required|string',
                'refer' => 'nullable|string',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $type_id = $request->type_id;
            if (!empty($request->new_type)) {
                $post_type = PostType::create([
                    'name' => $request->new_type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $type_id = $post_type->id;
            }

            if (empty($type_id)) {
                return response()->json([
                    'message' => 'Post type ID is missing.',
                ], 422);
            }

            $post = Post::create([
                'type_id' => $type_id,
                'profile_id' => $request->profile_id,
                'title' => $request->title,
                'content' => $request->content,
                'refer' => $request->refer,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!$post) {
                return response()->json([
                    'message' => "Post creation failed.",
                ], 500);
            }

            if ($request->hasFile('image_file')) {
                $image_file = $request->file('image_file');
                $image_data = file_get_contents($image_file->getRealPath());
                $image_data_base64 = base64_encode($image_data);

                $post_image = PostImage::create([
                    'post_id' => $post->id,
                    'image_data' => $image_data_base64,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }


            if (!$post_image) {
                return response()->json([
                    'message' => 'post image require false'
                ], 500);
            }

            return response()->json([
                'message' => 'Post created successfully.',
                'post' => $post
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "An error occurred while creating the post.",
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::with([
                'post_type',
                'post_pops',
                'post_images',
                'user_profiles',
            ])
                ->findOrFail($id);

            $post = [

                'postID' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'refer' => $post->refer,
                'postType' => [
                    'id' => $post->post_type->id,
                    'name' => $post->post_type->name,
                ],
                'postImage' => $post->post_images ? $post->post_images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'imageData' => $image->image_data,
                    ];
                }) : null,

            ];

            if (!$post) {
                return response()->json([
                    'message' => "api controller post function show require false",
                ], 404);
            }

            return response()->json([
                'message' => "api controller post function show require success.",
                'post' => $post,
            ], 200);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "api controller post function show error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    // Get Show Detail Post View
    public function getPostShowDetail(string $id)
    {
        try {
            $post = Post::with([
                'post_type',
                'post_pops',
                'post_image',
                'user_profiles.users.user_status',
            ])->findOrFail($id);

            $post = [
                'postID' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'refer' => $post->refer,
                'createdAT' => $post->created_at,
                'updatedAT' => $post->updated_at,
                'postType' => [
                    'typeID' => $post->post_type->id,
                    'name' => $post->post_type->name,
                ],
                'postImage' => $post->post_image ? $post->post_image = [
                    'imageID' => $post->post_image->id,
                    'imageData' => $post->post_image->image_data,
                ] : null,
                'userProfiles' => [
                    'profileID' => $post->user_profiles->id,
                    'titleName' => $post->user_profiles->title_name,
                    'firstName' => $post->user_profiles->first_name,
                    'lastName' => $post->user_profiles->last_name,
                    'nickName' => $post->user_profiles->nick_name,
                    'birthDay' => $post->user_profiles->birth_day,
                    'users' => $post->user_profiles->users ? [
                        'userID' => optional($post->user_profiles->users)->id,
                        'username' => optional($post->user_profiles->users)->username,
                        'email' => optional($post->user_profiles->users)->email,
                        'statusID' => optional($post->user_profiles->users)->status_id,
                        'status' => [
                            'id' => optional($post->user_profiles->users->user_status)->id,
                            'name' => optional($post->user_profiles->users->user_status)->name,
                        ],
                    ] : null,
                ],
                'postPops' => $post->post_pops ? $post->post_pops->map(function ($pop) {
                    return [
                        'popID' => optional($pop)->id,
                        'postID' => optional($pop)->post_id,
                        'profileID' => optional($pop)->profile_id,
                        'status' => optional($pop)->status,
                    ];
                }) : null,
            ];

            return response()->json([
                'message' => "api controller post function show require success.",
                'post' => $post,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "api controller post function show error",
                'error' => $error->getMessage(),
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     * Function Update Post
     */
    public function update(Request $request)
    {
        try {

            $validated = $request->validate([
                'profile_id' => 'required|integer',
                'post_id'    => 'required|integer',
                'title'      => 'nullable|string',
                'content'    => 'nullable|string',
                'refer'      => 'nullable|string',
                'type_id'    => 'nullable|integer',
                'new_type'   => 'nullable|string',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $post = Post::findOrFail($request->post_id);

            if (!$post) {
                return response()->json([
                    'message' => 'api update post where id false',
                    'request' => $request->all()
                ], 422);
            }

            $type_id = $validated['type_id'];

            if (!empty($validated['new_type'])) {
                $post_type = PostType::create([
                    'name' => $validated['new_type'],
                    'created_at' => now()
                ]);
                $type_id = $post_type->id;
            }

            if (!$type_id) {
                return response()->json([
                    'message' => 'api controller update post type id false',
                    'typeID' => $type_id
                ], 422);
            }

            $post->update([
                'type_id' => $type_id,
                'profile_id' => $validated['profile_id'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'refer' => $validated['refer'],
                'status' => 'active',
                'updated_at' => now(),
            ]);

            $image_data_base64 = null;
            if ($request->hasFile('image_file')) {
                $image_file = $request->file('image_file');
                $image_data = file_get_contents($image_file->getRealPath());
                $image_data_base64 = base64_encode($image_data);

                \App\Models\PostImage::updateOrCreate(
                    ['post_id' => $post->id],
                    [
                        'image_data' => $image_data_base64,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            return response()->json([
                'message' => 'api controller update post successfully.',
                'post' => $post->updated_at,
                'postImage' => $image_data_base64 ?? null
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "api controller function update post error",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            DB::beginTransaction();

            $post = Post::findOrFail($id);

            if (empty($post)) {
                return response()->json([
                    'message' => 'api controller function destroy where post id false.',
                ], 404);
            }

            $post->delete();

            DB::commit();

            return response()->json([
                'message' => "api controller function destroy post successfully.",
            ], 200);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "api controller function post error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function confirmUpdatePost(Request $request)
    {
        try {

            $validated = $request->validate([
                'profile_id' => 'required|integer',
                'post_id'    => 'required|integer',
                'title'      => 'required|string',
                'content'    => 'required|string',
                'refer'      => 'required|string',
                'type_id'    => 'required|integer',
                'new_type'   => 'required|string',
                'image_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $post = Post::findOrFail($request->post_id);

            if (empty($post) && empty($validated)) {
                return response()->json([
                    'message' => "api controller update post where id false and validated null",
                ], 422);
            }

            $type_id = $validated['type_id'];
            if (!empty($validated['newType'])) {
                $post_type = PostType::create([
                    'name' => $validated['newType'],
                    'created_at' => now()
                ]);
                $type_id = $post_type->id;
            }

            if (empty($type_id)) {
                return response()->json([
                    'message' => 'api controller update post type id false',
                    'typeID' => $type_id
                ], 422);
            }

            $post->updateOrCreate(
                ['id' => $post->id],
                [
                    'type_id' => $type_id,
                    'profile_id' => $validated['profile_id'],
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'refer' => $validated['refer'],
                    'status' => 'active',
                    'updated_at' => now(),
                ]
            );

            if ($request->hasFile('image_file')) {
                $image_file = $request->file('image_file');
                $image_data = file_get_contents($image_file->getRealPath());
                $image_data_base64 = base64_encode($image_data);

                // $post->post_images->update([
                //     'image_data' => $image_data_base64,
                //     'updated_at' => now()
                // ]);

                $post_image_id = $post->post_images->id;
                $post_image = PostImage::findOrFail($post_image_id);

                if (empty($post_image)) {
                    return response()->json([
                        'message' => 'api control update post check where post image id false',
                        'postImageID' => $post_image_id
                    ], 422);
                }

                $post_image->updateOrCreate(
                    ['id' => $post_image_id],
                    [
                        'post_id' => $post->id,
                        'image_data' => $image_data_base64,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            return response()->json([
                'message' => 'api controller update post successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "api controller function update post error",
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
