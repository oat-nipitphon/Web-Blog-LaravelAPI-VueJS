<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletCounter;
use App\Models\Reward;

class WalletCounterController extends Controller
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
        try {

            $validated = $request->validate([
                'wallet_id' => 'required|integer|exists:user_wallets,id',
                'point' => 'required|integer',
                'items' => 'required|array|min:1',
                'items.*.rewardID' => 'required|integer',
                'items.*.rewardName' => 'required|string',
                'items.*.rewardPoint' => 'required|integer',
                'items.*.rewardAmount' => 'required|integer',
            ]);

            $items = $request->input('items');

            $wallet = UserWallet::findOrFail($validated['wallet_id']);

            if (!$wallet) {
                return response()->json([
                    'message' => 'wallet where profile id false',
                    'walletID' => $request->wallet_id,
                ], 400);
            }

            $wallet->update([
                'point' => $validated['point'],
                'updated_at' => now()
            ]);

            if (!empty($request->input('items'))) {
                $statusCounter = false;
                foreach ($validated['items'] as $item) {

                    WalletCounter::create([
                        'wallet_id' => $validated['wallet_id'],
                        'reward_id' => $item['rewardID'],
                        'point' => $item['rewardPoint'],
                        'amount' => $item['rewardAmount'],
                        'status' => 'out',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $statusCounter = true;
                }
            }

            if ($statusCounter !== true) {

                return response()->json([
                    'userWallets' => $wallet,
                    'counterItems' => $statusCounter,
                    'message' => 'wallet counter store() false',
                ], 404);
            }

            return response()->json([
                'wallet' => $wallet,
                'statusCounter' => $statusCounter,
                'message' => 'wallet counter store() success.',
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'wallet counter store() function error',
                'error' => $error->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
    public function destroy(string $id) {}
}
