<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserProfileFollowers;
use App\Models\UserProfilePop;
use Illuminate\Http\Request;

class UserProfilePopController extends Controller
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

    public function profilePop(Request $request, string $profileID, string $authProfileID)
    {
        try {

            $pops = UserProfilePop::where('profile_id', $profileID)
                ->whereIn('profile_id_pop', $authProfileID)->first();

            $status_pop = null;

            if ($pops->status === true) {
                    $pops->delete();
                    $status_pop = false;
            } else {

               $pops = UserProfilePop::create([
                    'profile_id' => $profileID,
                    'profile_id_pop' => $authProfileID,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $status_pop = true;
            }

            if (empty($status_pop)) {
                return response()->json([
                    'controller profilePop() status pop null',
                    'statusPop' => $status_pop
                ], 404);
            }

            return response()->json([
                'message' => 'controller profilePop() success.',
                'pops' => $pops,
                'statusPop' => $status_pop
            ], 201);

        } catch (\Exception $error) {
            return response()->json([
                'message' => "controller profilePop() error",
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
