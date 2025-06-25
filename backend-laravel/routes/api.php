<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserProfilePopController;
use App\Http\Controllers\UserProfileFollowersController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostStoreController;
use App\Http\Controllers\PostPopController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletCounterController;

use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminRewardController;
use App\Http\Controllers\AdminUserProfileController;
use App\Http\Controllers\AdminWalletController;

use App\Models\UserStatus;
use App\Models\PostType;
use App\Models\RewardStatus;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
|  ************************  Test Git Create Branch ***************************
*/


Route::get('/get_user_status', function () {
    $userStatus = UserStatus::all();
    return response()->json($userStatus, 200);
});

Route::post('/register/check_email', [AuthController::class, 'registerCheckEmail']);
Route::post('/register/check_username', [AuthController::class, 'registerCheckUsername']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('/')->group(function () {


    Route::apiResource('/users', UserController::class);
    Route::apiResource('/user_profiles', UserProfileController::class);
    Route::post('/user_profiles/upload_image', [UserProfileController::class, 'uploadImageProfile']);

    Route::apiResource('/user_profile_pops', UserProfilePopController::class);
    Route::post('/pop_like/{postUserID}/{authUserID}', [UserProfilePopController::class, 'popLikeProfile']);
    Route::apiResource('/user_profile_followers', UserProfileFollowersController::class);
    Route::post('/followers/{postUserID}/{authUserID}', [UserProfileFollowersController::class, 'followersProfile']);


    Route::get('/get_post_types', function () {
        $postTypes = PostType::all();
        return response()->json($postTypes, 200);
    });
    Route::apiResource('/posts', PostController::class);

    Route::prefix('/posts')->group(function () {
        Route::apiResource('/stores', PostStoreController::class);

        Route::get('/stores_report/{profileID}', [PostStoreController::class, 'storeReportPosts']);
        Route::post('/confirm_store/{postID}', [PostStoreController::class, 'confirmStorePost']);
        Route::post('/recover/{postID}', [PostStoreController::class, 'recoverPost']);
        Route::post('/recoverSelected', [PostStoreController::class, 'recoverPostSelectd']);
        Route::post('/deleteSelected', [PostStoreController::class, 'deletePostsSelectd']);

        Route::post('/event_pop/{postID}/{profileID}/{status}', [PostPopController::class, 'postPop']);
    });


    Route::apiResource('/rewards', RewardController::class);
    Route::prefix('/rewards')->group(function () {
        Route::get('/report_card_items/{profileID}', [RewardController::class,]);
    });

    Route::apiResource('/wellets', WalletController::class);
    Route::prefix('/wellets')->group(function () {
        Route::apiResource('/counters', WalletCounterController::class);
        Route::prefix('/cartItems')->group(function () {
            Route::post('/userConfirmSelectReward', [WalletCounterController::class, 'userConfirmSelectReward']);
            Route::get('/getReportReward/{userID}', [WalletCounterController::class, 'getReportReward']);
            Route::post('/cancel_reward/{itemID}', [WalletCounterController::class, 'cancelReward']);
        });
    });

    Route::prefix('/manager')->group(function () {

        Route::apiResource('/user_profiles', AdminUserProfileController::class);

        Route::apiResource('/posts', AdminPostController::class);
        Route::prefix('/posts')->group(function () {
            Route::post('/blockOrUnBlock/{postID}/{blockStatus}', [AdminPostController::class, 'blockOrUnBlockPost']);
        });

        Route::apiResource('/rewards', AdminRewardController::class);
        Route::prefix('/rewards')->group(function () {
            Route::post('/updateStatusReward/{rewardID}', [AdminRewardController::class, 'updateStatusReward']);
        });

        Route::apiResource('/wellets', AdminWalletController::class);
    });
})->middleware('auth:sanctum');

// Auth Login Get Data Account
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    try {

        $user_req = $request->user();
        $users = User::with([
            'user_status',
            'user_wallet',
            'user_wallet.wallet_counters',
            'user_profile',
            'user_profile.profile_image'

        ])->findOrFail($user_req->id);

        $token = $users->createToken($users->username)->plainTextToken;

        $users =  [
            'id' => $users->id,
            'name' => $users->name,
            'email' => $users->email,
            'username' => $users->username,
            'status_id' => $users->status_id,
            'created_at' => $users->created_at,
            'updated_at' => $users->updated_at,

            'userStatus' => $users->user_status ? [
                'id' => $users->user_status->id,
                'name' => $users->user_status->name,
                'code' => $users->user_status->code,
            ] : null,


            'userProfile' => $users->user_profile ? [
                'id' => $users->user_profile->id,
                'titleName' => $users->user_profile->title_name,
                'firstName' => $users->user_profile->first_name,
                'lastName' => $users->user_profile->last_name,
                'nickName' => $users->user_profile->nick_name,
                'birthDay' => $users->user_profile->birth_day,
            ] : null,


            'profileImage' => $users->user_profile && $users->user_profile->profile_image ? [
                'id' => $users->user_profile->profile_image->id,
                'profile_id' => $users->user_profile->profile_image->profile_id,
                'imageData' => $users->user_profile->profile_image->image_data ?
                    base64_encode($users->user_profile->profile_image->image_data) : null,
            ] : null,

            'wallet' => $users->user_wallet ? [
                'id' => $users->user_wallet->id,
                'userID' => $users->user_wallet->user_id,
                'point' => $users->user_wallet->point,
                'status' => $users->user_wallet->status,
            ] : null,


            'walletCounters' => $users->user_wallet->wallet_counters ?
                $users->user_wallet->wallet_counters->map(function ($counter) {
                    return [
                        'id' => $counter->id,
                        'walletID' => $counter->wallet_id,
                        'rewardID' => $counter->reward_id,
                        'point' => $counter->point,
                        'status' => $counter->reward_id,
                        'detail' => $counter->detail,
                        'createdAt' => $counter->created_at,
                        'updatedAt' => $counter->updated_at,
                    ];
                }) : null,
        ];

        if (empty($users)) {
            return response()->json([
                'message' => 'get user false',
                'users' => '',
                'token' => ''
            ], 404);
        }

        return response()->json([
            'message' => 'get user successfllry.',
            'users' => $users,
            'token' => $token
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'get user function error',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Reset Password
Route::post('/reset-password', function (Request $request) {

    $request->validate([
        // 'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation'),
        // $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);


    if (!$status) {
        return response()->json([
            'message' => 'route api reset password false',
            'statusReset' => $status
        ], 400);
    }

    return response()->json([
        'message' => 'route api reset password success',
        'statusReset' => $status
    ], 200);
});
// ->middleware('guest')->name('password.update');
