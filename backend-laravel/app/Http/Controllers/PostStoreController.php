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
            // Validate input
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:post_stores,id',
            ]);

            $ids = $request->input('ids');

            if (empty($ids)) {
                return response()->json([
                    'message' => 'ไม่มีรายการ ID ที่ส่งมา',
                    'ids' => $ids
                ], 404);
            }

            // ดึงรายการ PostStore
            $post_stores = PostStore::whereIn('id', $ids)->get();
            $recovered_posts = [];

            foreach ($post_stores as $store) {
                $post = Post::find($store->post_id);
                if ($post) {
                    $post->status = 'active';
                    $post->save();
                    $recovered_posts[] = $post;
                    $store->delete();
                }
            }

            return response()->json([
                'message' => 'กู้คืนบทความที่เลือกเรียบร้อยแล้ว',
                'recovered' => $recovered_posts
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการกู้คืนบทความ',
                'error' => $error->getMessage()
            ], 500);
        }
    }



    public function deletePostsSelectd(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:post_stores,id',
            ]);

            $ids = $request->input('ids');

            if (empty($ids)) {
                return response()->json([
                    'message' => 'ไม่มีรายการ ID ที่ส่งมา',
                    'ids' => $ids
                ], 404);
            }

            DB::beginTransaction();

            $post_stores = PostStore::whereIn('id', $ids)->get();
            $deleted_posts = [];

            foreach ($post_stores as $store) {
                $post = Post::find($store->post_id);
                if ($post) {
                    $post->delete(); // soft delete หรือ hard delete ตามที่ Model ตั้งไว้
                    $deleted_posts[] = $post;
                }

                $store->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'ลบบทความและ post_stores ที่เลือกสำเร็จ',
                'deleted_posts' => $deleted_posts
            ], 200);
        } catch (\Exception $error) {
            DB::rollBack();

            return response()->json([
                'message' => "เกิดข้อผิดพลาดในการลบโพสต์",
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
