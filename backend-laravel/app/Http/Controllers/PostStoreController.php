<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostStoreController extends Controller
{

    public function storeReportPosts(Request $request, string $profileID)
    {
        try {


            $profileIDs = is_array($profileID) ? $profileID : [$profileID];

            $posts = PostStore::with(['posts'])->where('status', 'true')
                ->whereHas('posts', function ($query) use ($profileIDs) {
                    $query->whereIn('profile_id', $profileIDs);
                })
                ->get();

            if (empty($posts)) {
                return response()->json([
                    'message' => 'controller get posts store where profile id false',
                    'profileID' => $profileIDs
                ], 400);
            }

            return response()->json([
                'message' => 'controller store report posts',
                'posts' => $posts,
            ], 200);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "controller function recover get post error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function confirmStorePost(string $postID)
    {
        try {
            $post = Post::findOrFail($postID);

            if (empty($post)) {
                return response()->json([
                    'message' => "controller confirm store post where id false",
                    'postID' => $postID,
                ], 400);
            }

            // อัปเดตสถานะโพสต์
            $post->update([
                'status' => 'store',
            ]);

            $post_store = PostStore::updateOrCreate(
                ['post_id' => $post->id],
                [
                    'status' => 'true',
                ]
            );

            if ($post_store->post_id != $postID) {
                return response()->json([
                    'message' => 'controller insert post store table false',
                    'postStore' => $post->post_store
                ], 400);
            }

            return response()->json([
                'message' => "controller insert post store success",
                'post' => $post->post_store
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "controller insert post store function error",
                'error' => $error->getMessage()
            ], 500);
        }
    }


    public function updateStorePosts(Request $request)
    {
        try {

            $ids = $request->ids;

            if (!is_array($ids) || empty($ids)) {
                return response()->json([
                    'message' => 'laravel update store posts response array false',
                    'reqPostIDs' => $request->ids
                ], 404);
            }


            $posts = Post::whereIn('id', $ids)->get();

            foreach ($posts as $post) {
                $post->update([
                    'status' => 'active',
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'message' => 'laravel update posts selected success',
                'recoverPosts' => $posts
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'laravel update posts selected function error',
                'error' => $error->getMessage()
            ]);
        }
    }

    public function destroyPosts(Request $request)
    {
        try {

            DB::beginTransaction();

            Post::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'message' => "laravel delete posts success",
            ], 200);
        } catch (\Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => "laravel delete posts function error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $posts = PostStore::with(['posts'])
                ->where('status', 'true')
                ->get();

            if (empty($posts)) {
                return response()->json([
                    'message' => 'controller get posts store false',
                ], 404);
            }

            return response()->json([
                'message' => 'controller get posts store success',
                'posts' => $posts
            ], 200);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "controller function recover get post error",
                'error' => $error->getMessage()
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(PostStore $postStore, string $profileID)
    {
        try {

            $profileIDs = is_array($profileID) ? $profileID : [$profileID];

            $postStores = PostStore::with(['posts'])->where('status', 'true')
                ->whereHas('posts', function ($query) use ($profileIDs) {
                    $query->whereIn('profile_id', $profileIDs);
                })
                ->get();

            return response()->json([
                'message' => 'PostStoreController show',
                'postStores' => $postStores,
                'postStore' => $postStore
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'PostStoreController function error',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * Recover Store Post Status Active
     */
    public function update(Request $request, PostStore $postStore, string $postID) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostStore $postStore, string $id)
    {
        try {

            DB::beginTransaction();

            Post::with([
                'post_images',
                'post_pops',
                'post_comments',
                'post_office_files',
                'post_videos'
            ])->findOrFail($id);

            if (empty($post)) {
                return response()->json([
                    'message' => 'laravel delete post where post id false',
                    'postID' => $id
                ], 404);
            }

            $post->delete();

            DB::commit();

            return response()->json([
                'message' => "laravel delete post success",
            ], 200);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "laravel delete post function error",
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
