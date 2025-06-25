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
    public function confirmStorePost(string $postID)
    {
        try {
            $post = Post::findOrFail($postID);

            // อัปเดตสถานะโพสต์
            $post->update([
                'status' => 'store',
            ]);

            // เพิ่มหรืออัปเดตข้อมูลใน post_stores
            $post_store = PostStore::updateOrCreate(
                ['post_id' => $post->id],
                ['status' => 'true']
            );

            return response()->json([
                'message' => "บันทึกโพสต์ในตาราง post_stores สำเร็จ",
                'post_store' => $post_store,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "เกิดข้อผิดพลาดระหว่างการบันทึกโพสต์",
                'error' => $error->getMessage()
            ], 500);
        }
    }



    public function storeReportPosts(Request $request, string $profileID)
    {
        try {

            $profileIDs = is_array($profileID) ? $profileID : [$profileID];

            $posts = PostStore::with(['posts.post_images'])->where('status', 'true')
                ->whereHas('posts', function ($query) use ($profileIDs) {
                    $query->whereIn('profile_id', $profileIDs);
                    $query->where('status', 'store');
                })
                ->get();
            // ->map(function ($post) {
            //     return [

            //         'id' => $post->id,
            //         'status' => $post->status,

            //         'post' => $post->posts ? [
            //             'id' => optional($post->posts)->id,
            //             'title' => optional($post->posts)->title,
            //             'status' => optional($post->posts)->status,
            //             'created_at' => optional($post->posts)->created_at,
            //             'updated_at' => optional($post->posts)->updated_at,

            //             'postImage' => $post->posts->post_images ? [
            //                 'id' => optional($post->posts->post_images)->id,
            //                 'imageData' => optional($post->posts->post_images)->image_data
            //             ] : null,

            //         ] : null,
            //     ];
            // });

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


    public function recoverPost(Request $request, string $postID)
    {
        try {

            $post_store = PostStore::findOrFail($postID);

            if (!$post_store) {
                return response()->json([
                    'message' => 'recover post store where false',
                    'postID' => $postID
                ]);
            }

            $post = Post::findOrFail($post_store->post_id);

            if (!$post) {
                return response()->json([
                    'message' => 'recover post where false',
                    'postID' => $post_store->post_id
                ], 400);
            }

            $post->update([
                'status' => 'active',
                'updated_at' => now()
            ]);

            $post_store->delete();

            return response()->json([
                'message' => 'recover post update status success',
                'post' => $post
            ], 201);
        } catch (\Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => 'recover post update status function error',
                'error' => $error->getMessage()
            ], 500);
        }
    }


    public function recoverPostSelectd(Request $request)
    {
        try {

            // $request->validate([
            //     'ids' => 'required|array',
            //     'ids.*' => 'integer|exists:posts,id',
            // ]);

            $ids = $request->ids;

            if (!is_array($ids) || empty($ids)) {
                return response()->json([
                    'message' => 'controller recoverPostSelectd() check array ids false',
                    'ids' => $request->ids
                ], 404);
            }

            $posts = Post::whereIn('id', $ids)->get();
            $counter_check = [];

            $post_stores = PostStore::whereIn('post_id', $ids);

            foreach ($posts as $post) {

                $post->update([
                    'status' => 'active'
                ]);

                $post_stores->delete();

                $counter_check[] = $post;
            }

            return response()->json([
                'message' => 'update status recover success',
                'posts' => $posts,
                'counters' => $counter_check,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'laravel update posts selected function error',
                'error' => $error->getMessage()
            ]);
        }
    }


    public function destroyPosts(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:posts,id',
        ]);

        try {
            DB::beginTransaction();

            // ถ้าคุณมีข้อมูลเกี่ยวข้องให้ลบด้วย เช่น PostImage, PostComment
            // PostImage::whereIn('post_id', $request->ids)->delete();

            Post::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'message' => "ลบบทความที่เลือกเรียบร้อยแล้ว",
            ], 200);
        } catch (\Exception $error) {
            DB::rollBack();

            return response()->json([
                'message' => "เกิดข้อผิดพลาดในการลบข้อมูล",
                'error' => $error->getMessage(),
            ], 500);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index() {}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(PostStore $postStore, string $profileID) {}

    /**
     * Update the specified resource in storage.
     * Recover Store Post Status Active
     */
    public function update(Request $request, PostStore $postStore, string $postID) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostStore $postStore, string $id) {}
}
