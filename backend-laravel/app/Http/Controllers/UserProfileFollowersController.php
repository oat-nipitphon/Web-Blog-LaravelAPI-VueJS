<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfileFollowers;

class UserProfileFollowersController extends Controller
{

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

    public function profileFollowers(Request $request, string $profileID, string $authProfileID)
    {
        try {

            $followers = UserProfileFollowers::where('profile_id', $profileID)
                ->where('profile_id_followers', $authProfileID)
                ->first();

            $status_followers = null;

            if ($followers->status === true) {
                $followers->delete();
                $status_followers = false;
            } else {

                $followers = UserProfileFollowers::create([
                    'profile_id' => $profileID,
                    'profile_id_followers' => $authProfileID,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $status_followers = true;
            }

            if (empty($status_followers)) {
                return response()->json([
                    'controller profileFollowers() status followers null',
                    'statusPop' => $status_followers
                ], 404);
            }

            return response()->json([
                'message' => 'controller profileFollowers() success',
                'followers' => $followers,
                'statusFollowers' => $status_followers
            ], 201);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "controller profileFollowers() error",
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
