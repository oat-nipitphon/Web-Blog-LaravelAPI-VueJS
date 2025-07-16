<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Reward;
use App\Models\RewardImage;
use App\Models\RewardStatus;

class RewardController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $rewards = Reward::with(['reward_status', 'reward_images'])->get();


            if (!$rewards) {
                return response()->json([
                    'message' => 'reward index() get false.',
                ], 400);
            }

            return response()->json([
                'message' => 'reward index() get success.',
                'rewards' => $rewards,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'controller reward index function error',
                'error' => $error->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string',
                'point' => 'required|numeric',
                'amount' => 'required|integer',
                'status_id' => 'nullable|string',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $reward = Reward::create([
                'name' => $validated['name'],
                'point' => $validated['point'],
                'amount' => $validated['amount'],
                'status_id' => $validated['status_id'],
            ]);

            if (empty($reward->id)) {
                return response()->json([
                    'message' => 'reward create new false',
                    'reward' => $reward
                ], 400);
            }

            if ($request->hasFile('image_file')) {
                $image_file = $request->file('image_file');
                $image_data = file_get_contents($image_file->getRealPath());
                $image_data_base64 = base64_encode($image_data);
            } else {
                $image_data_base64 = null;
            }

            RewardImage::create([
                'reward_id' => $reward->id,
                'image_data' => $image_data_base64,
                'created_at' => now(),
            ]);

            return response()->json([
                'message' => 'reward create new success',
                'reward' => $reward,
                'imageDataBase64' => $image_data_base64,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'reward create new function error',
                'error' => $error->getMessage()
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $reward = Reward::with([
                'reward_status',
                'reward_images'
            ])->findOrFail($id);

            if (empty($reward)) {
                return response()->json([
                    'message' => 'reward show() get false',
                    'id' => $id
                ], 400);
            }

            return response()->json([
                'message' => 'reward show() get success',
                'rewards' => $reward
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'reward show() get function error',
                'error' => $error->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {

            $validated = $request->validate([
                'name' => 'required|string',
                'point' => 'required|numeric',
                'amount' => 'required|integer',
                'status_id' => 'nullable|string',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            $reward = Reward::findOrFail($id);

            if (empty($reward)) {
                return response()->json([
                    'message' => 'reward update() false',
                    'id' => $id
                ], 400);
            }

            $reward->update(
                [
                    'name' => $validated['name'],
                    'point' => $validated['point'],
                    'amount' => $validated['amount'],
                    'status_id' => $validated['status_id'],
                    'updated_at' => now()
                ]
            );

            $reward_image = RewardImage::where('reward_id', $reward->id)->first();

            if (!$reward_image) {
                if ($request->hasFile('image_file')) {
                    $image_file = $request->file('image_file');
                    $image_data = file_get_contents($image_file->getRealPath());
                    $image_data_base64 = base64_encode($image_data);
                } else {
                    $image_data_base64 = null;
                }

                $reward_image->updateOrCreate(
                    ['reward_id' => $reward->id],
                    [
                        'image_data' => $image_data_base64,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            return response()->json([
                'message' => 'reward update() success',
                'reward' => $reward
            ], 201);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'reward update() function error',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {

            DB::beginTransaction();

            $reward = Reward::findOrFail($id);

            if (!$reward) {
                return response()->json([
                    'message' => 'reward delete() where id false',
                    'rewardID' => $id
                ], 400);
            }

            $reward->delete();
            DB::commit();

            return response()->json([
                'message' => 'reward delete() success',
                'status' => 200,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'reward delete() function error',
                'error' => $error->getMessage()
            ]);
        }
    }
}
