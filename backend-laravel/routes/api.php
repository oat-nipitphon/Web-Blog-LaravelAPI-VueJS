<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use App\Models\UserStatus;
use App\Models\PostType;
use App\Models\Reward;
use App\Models\RewardStatus;
use App\Models\User;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserProfilePopController;
use App\Http\Controllers\UserProfileFollowersController;
use App\Http\Controllers\UserProfileContactController;

use App\Http\Controllers\PostController;
use App\Http\Controllers\PostStoreController;
use App\Http\Controllers\PostPopController;

use App\Http\Controllers\RewardController;

use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletCounterController;

use App\Http\Controllers\ManagerBlogController;

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

    // --------------------------------------- User Profile ------------------------------------------------ //

    Route::apiResource('/users', UserController::class);
    Route::apiResource('/user_profiles', UserProfileController::class);
    Route::apiResource('/user_profile_contacts', UserProfileContactController::class);
    Route::post('/user_profiles/upload_image', [UserProfileController::class, 'uploadImageProfile']);

    route::prefix('/profile')->group(function () {
        Route::post('/followers/{profileID}/{profileIDEvent}', [UserProfileFollowersController::class, 'profileFollowers']);
        Route::post('/pop/{profileID}/{profileIDEvent}', [UserProfilePopController::class, 'profilePop']);
    });

    // --------------------------------------- User Profile ------------------------------------------------ //

    // --------------------------------------- Post ------------------------------------------------ //
    Route::get('/get_post_types', function () {
        $post_types = PostType::all();
        return response()->json([
            'postTypes' => $post_types
        ], 200);
    });
    Route::apiResource('/posts', PostController::class);
    Route::get('/post/show-detail/{id}', [ PostController::class, 'getPostShowDetail']);
    Route::prefix('/posts')->group(function () {
        Route::apiResource('/stores', PostStoreController::class);
        Route::get('/stores_report/{profileID}', [PostStoreController::class, 'storeReportPosts']);
        Route::post('/confirm_store/{postID}', [PostStoreController::class, 'confirmStorePost']);
        Route::post('/recover/{postID}', [PostStoreController::class, 'recoverPost']);
        Route::post('/recoverSelected', [PostStoreController::class, 'recoverPostSelectd']);
        Route::post('/deleteSelected', [PostStoreController::class, 'deletePostsSelectd']);
        Route::post('/event_pop/{postID}/{profileID}/{status}', [PostPopController::class, 'postPop']);
    });
    // --------------------------------------- Post ------------------------------------------------ //

    // --------------------------------------- Reward ------------------------------------------------ //
    Route::get('/rewards/get_reward_status', function () {
        $reward_status = RewardStatus::all();
        return response()->json([
            'message' => 'api get reward status',
            'rewardStatus' => $reward_status
        ], 200);
    });
    Route::apiResource('/rewards', RewardController::class);
    Route::prefix('/reward')->group(function () {

        Route::get('/report_card_items/{profileID}', [RewardController::class]);
    });
    // --------------------------------------- Reward ------------------------------------------------ //

    // --------------------------------------- Wellet ------------------------------------------------ //
    Route::apiResource('/wellets', WalletController::class);

    Route::prefix('/wellets')->group(function () {

        Route::apiResource('/counters', WalletCounterController::class);

        Route::prefix('/cartItems')->group(function () {

            // Save insert selectd items
            Route::post('/userConfirmSelectReward', [WalletCounterController::class, 'userConfirmSelectReward']);

            // Report selectd items
            Route::get('/getReportReward/{userID}', [WalletCounterController::class, 'getReportReward']);

            // Cancel item selectd
            Route::post('/cancel_reward/{itemID}', [WalletCounterController::class, 'cancelReward']);
        });
    });
    // --------------------------------------- Wellet ------------------------------------------------ //


    // ***************************************************************************************************** //
    // --------------------------------------- Manager Blog ------------------------------------------------ //
    Route::prefix('/manager')->group(function () {

        Route::prefix('/user_profiles')->group(function () {
            Route::get('/get_reports', [ManagerBlogController::class, 'managerGetUserProfiles']);
            route::post('/status_account/update', [ManagerBlogController::class, 'managerUpdateStatusAccount']);
        });


        Route::prefix('/posts')->group(function () {
            Route::get('/get_reports', [ManagerBlogController::class, 'managerGetPosts']);
            Route::post(
                '/set_status_post/{id}/{status}',
                [ManagerBlogController::class, 'managerEventSetStatusPost']
            );
        });

        Route::prefix('/rewards')->group(function () {
            Route::get('/get_reports', [ManagerBlogController::class, 'managerGetRewards']);
            Route::post('/set_status_reward/{id}', [ManagerBlogController::class, 'managerSetStatusReward']);
        });

        Route::prefix('/wallets')->group(function () {
            Route::get('/get_reports', [ManagerBlogController::class, 'managerGetWellets']);
        });
    });
    // --------------------------------------- Manager Blog ------------------------------------------------ //
    // ***************************************************************************************************** //


})->middleware('auth:sanctum');

// --------------------------------------- Authorization ------------------------------------------------ //
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    try {

        $user_req = $request->user();
        $users = User::with([
            'user_status',
            'user_wallet',

            'user_profile',
            'user_profile.profile_image',
            'user_profile.profile_contacts',
            'user_profile.profile_pops',
            'user_profile.profile_followers',

        ])->findOrFail($user_req->id);

        $token = $users->createToken($users->username)->plainTextToken;

        $users =  [

            'id' => optional($users)->id,
            'name' => optional($users)->name,
            'email' => optional($users)->email,
            'username' => optional($users)->username,
            'status_id' => optional($users)->status_id,
            'created_at' => optional($users)->created_at,
            'updated_at' => optional($users)->updated_at,

            'userStatus' => $users->user_status ? [
                'id' => optional($users->user_status)->id,
                'name' => optional($users->user_status)->name,
                'code' => optional($users->user_status)->code,
            ] : null,

            'userProfile' => $users->user_profile ? [
                'id' => optional($users->user_profile)->id,
                'titleName' => optional($users->user_profile)->title_name,
                'firstName' => optional($users->user_profile)->first_name,
                'lastName' => optional($users->user_profile)->last_name,
                'nickName' => optional($users->user_profile)->nick_name,
                'birthDay' => optional($users->user_profile)->birth_day,
            ] : null,

            'profileImage' => $users->user_profile && $users->user_profile->profile_image ? [
                'id' => optional($users->user_profile->profile_image)->id,
                'profile_id' => optional($users->user_profile->profile_image)->profile_id,
                'imageData' => optional($users->user_profile->profile_image)->image_data,
            ] : null,

            'wallet' => $users->user_wallet ? [
                'id' => optional($users->user_wallet)->id,
                'userID' => optional($users->user_wallet)->user_id,
                'point' => optional($users->user_wallet)->point,
                'status' => optional($users->user_wallet)->status,
            ] : null,

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
// --------------------------------------- Authorization ------------------------------------------------ //

// --------------------------------------- Test Reset Password ------------------------------------------------ //
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
// --------------------------------------- Test Reset Password ------------------------------------------------ //
