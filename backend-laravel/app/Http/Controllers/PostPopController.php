<?php

namespace App\Http\Controllers;

use App\Models\PostPop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostPopController extends Controller
{

    public function postPop(string $postID, string $profileID, string $status)
    {
        try {

            $post_pop = PostPop::where('post_id', $postID)
                ->where('profile_id', $profileID)->first();

            $check_status_pop = '';

            if ($post_pop) {

                if ($post_pop->status === $status) {
                    $post_pop->delete();
                    $check_status_pop = 'post_pop->status (delete)';
                } else {
                    $post_pop->update([
                        'status' => $status,
                        'updated_at' => now()
                    ]);
                    $check_status_pop = 'post_pop->status (update)';
                }
            } else {
                PostPop::create([
                    'post_id' => $postID,
                    'profile_id' => $profileID,
                    'status' => $status,
                    'created_at' => now()
                ]);
            }


            return response()->json([
                'message' => 'controller post pop success',
                'checkStatusPop' => $check_status_pop
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'controller function post pop error ',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function popLike(string $profileIDPop, string $postID, string $statusPop)
    {
        try {

            $popLike = PostPop::where('profile_id_pop', $profileIDPop)
                ->where('post_id', $postID)
                ->first();

            $checkStatusPop = '';

            if ($popLike) {

                if ($popLike->status === $statusPop) {
                    $popLike->delete();
                    $checkStatusPop = 'delete status pop like';
                } else {
                    $popLike->update(['status' => $statusPop]);
                    $checkStatusPop = 'update status pop like';
                }
            } else {

                PostPop::create([
                    'post_id' => $postID,
                    'profile_id' => $profileIDPop,
                    'status' => $statusPop,
                ]);
                $checkStatusPop = 'create status pop like';
            }

            if (empty($checkStatusPop)) {
                return response()->json([
                    'message' => 'laravel update status pop like false',
                    'checkStatusPop' => $checkStatusPop
                ], 404);
            }

            return response()->json([
                'message' => 'laravel update status pop like success',
                'checkStatusPop' => $checkStatusPop
            ], 201);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "laravel update status pop like function error",
                'error' => $error->getMessage()
            ], 500);
        }
    }


    /**
     * Pop dis loke post
     */
    public function popDisLike(string $profileIDPop, string $postID, string $statusPop)
    {
        try {

            $popDisLike = PostPop::where('profile_id_pop', $profileIDPop)
                ->where('post_id', $postID)
                ->first();

            $checkStatusPop = '';

            if ($popDisLike) {

                if ($popDisLike->status === $statusPop) {
                    $popDisLike->delete();
                    $checkStatusPop = 'delete status pop dis like';
                } else {
                    $popDisLike->update(['status' => $statusPop]);
                    $checkStatusPop = 'update status pop dis like';
                }
            } else {

                PostPop::create([
                    'post_id' => $postID,
                    'profile_id' => $profileIDPop,
                    'status' => $statusPop,
                ]);
                $checkStatusPop = 'create status pop dis like';
            }

            if (empty($checkStatusPop)) {
                return response()->json([
                    'message' => 'laravel update status pop dis like false',
                    'checkStatusPop' => $checkStatusPop
                ], 404);
            }

            return response()->json([
                'message' => 'laravel update status pop like dis success',
                'checkStatusPop' => $checkStatusPop
            ], 201);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "laravel update status pop like function error",
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    /**
     * Pop like post
     */
}
