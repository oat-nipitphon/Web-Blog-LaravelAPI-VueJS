<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserProfileFollowers;
use App\Models\UserProfilePop;
use Illuminate\Http\Request;

class UserProfilePopController extends Controller
{


    public function profileEventPop(string $profileID, string $profileIDpop)
    {
        try {

            $pops = UserProfilePop::where('profile_id', $profileID)
                ->where('profile_id_pop', $profileIDpop)->first();

            $statusPop = false;

            if ($pops) {

                if ($pops->status === 'true') {
                    $pops->delete();
                    $statusPop = 'true';
                } else {
                    $pops->update([
                        'status' => 'true',
                        'updated_at' => now(),
                    ]);
                    $statusPop = 'true';
                }
            } else {
                UserProfilePop::create([
                    'profile_id' => $profileID,
                    'profile_id_pop' => $profileIDpop,
                    'status' => 'true'
                ]);
            }

            if (!$pops) {
                return response()->json([
                    'profileID' => $profileID,
                    'profileIDpop' => $profileIDpop
                ], 400);
            }

            return response()->json([
                'pops' => $pops,
                'statusPop' => $statusPop
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "profileEventPop() error",
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
