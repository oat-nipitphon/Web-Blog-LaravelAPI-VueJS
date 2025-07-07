<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfileFollowers;

class UserProfileFollowersController extends Controller
{

    public function profileEventFollowers(string $profileID, string $profileIDfollowers)
    {
        try {

            $followers = UserProfileFollowers::where('profile_id', $profileID)
                ->where('profile_id_followers', $profileIDfollowers)
                ->first();

            $statusFollowers = '';

            if (!empty($followers)) {

                if ($followers->status === 'true') {
                    $followers->delete();
                    $statusFollowers = 'false';
                } else {
                    $followers->update([
                        'status' => 'true',
                        'updated_at' => now(),
                    ]);
                    $statusFollowers = 'true';
                }
            } else {

                UserProfileFollowers::create([
                    'profile_id' => $profileID,
                    'profile_id_followers' => $profileIDfollowers,
                    'status' => 'true',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $statusFollowers = 'true';
            }

            if (empty($statusFollowers)) {

                return response()->json([
                    'message' => 'profileEventFollowers() check status false',
                    'statusFollowers' => $statusFollowers
                ], 404);
            }

            return response()->json([
                'message' => 'profileEventFollowers() success',
                'statusFollowers' => $statusFollowers
            ], 200);
        } catch (\Exception $error) {

            return response()->json([
                'message' => "profileEventFollowers() error",
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
}
