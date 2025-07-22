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

    public function profilePop(Request $request, string $profileID, string $profileIDEvent)
    {
        try {
            // ตรวจสอบว่ามี record อยู่หรือไม่
            $pops = UserProfilePop::where('profile_id', $profileID)
                ->where('profile_id_pop', $profileIDEvent)
                ->first();

            $status_pop = 'null';

            if ($pops) {
                if ($pops->status === 'true') {
                    // ถ้ามีและ status = true → ลบ
                    $pops->delete();
                    $status_pop = 'false';

                    return response()->json([
                        'message' => 'Pop removed successfully.',
                        'statusPop' => $status_pop
                    ], 200);
                }
            }

            // ถ้าไม่มี record หรือ status != true → สร้างใหม่
            $pops = UserProfilePop::create([
                'profile_id' => $profileID,
                'profile_id_pop' => $profileIDEvent,
                'status' => 'true',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $status_pop = 'true';

            return response()->json([
                'message' => 'Pop created successfully.',
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
