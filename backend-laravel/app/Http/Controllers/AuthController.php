<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserLogLogin;
use App\Models\UserWallet;

class AuthController extends Controller
{

    // Register Check Email
    public function registerCheckEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    // Register Check Username
    public function registerCheckUsername(Request $request)
    {
        $exists = User::where('username', $request->username)->exists();
        return response()->json(['exists' => $exists]);
    }

    // Register
    public function register(Request $request)
    {
        try {

            $validate = $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email',
                'username' => [
                    'required',
                    'string',
                    'min:4',
                    'max:30',
                    'unique:users,username'
                ],
                'password' => [
                    'required',
                    'string',
                    Password::min(3)
                        // ->letters() // ต้องมี ตัวอักษร (a-z A-Z)
                        ->numbers() // ต้องมี ตัวเลข
                        // ->symbols() // ต้องมี สัญลักษณ์พิเศษ
                        ->uncompromised()
                ],
                'statusID' => ['required', 'integer'],
            ], [
                'password.*' => 'Password must be at least 8 characters and include letters, numbers, and symbols.',
                'email.unique' => 'This email is already registered.',
                'username.unique' => 'This username is already taken.',
            ]);

            $user = User::create([
                'name' => $validate['username'],
                'username' => $validate['username'],
                'email' => $validate['email'],
                'password' => Hash::make($validate['password']),
                'status_id' => $validate['statusID'],
                'status_account' => 'active',
                'created_at' => now()
            ]);

            if (!empty($user)) {
                $userProfile = UserProfile::create([
                    'user_id' => $user->id,
                    'nick_name' => $validate['username'],
                    'created_at' => now()
                ]);

                $userWallet = UserWallet::create([
                    'user_id' => $user->id,
                    'point' => 0,
                    'status' => 'active',
                    'created_at' => now(),
                ]);

                $token = $user->createToken($user->username)->plainTextToken;

                return response()->json([
                    'message' => 'register successfully',
                    'user' => $user,
                    'userProfile' => $userProfile,
                    'userWallet' => $userWallet,
                    'token' => $token
                ], 201);
            }


            return response()->json([
                'message' => 'laravel function register response false'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "register function error",
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Login
    public function login(Request $request)
    {
        try {

            $request->validate([
                'emailUsername' => 'required|string|min:3|max:255',
                'password' => 'required|string|min:3|max:255',
            ], [
                'emailUsername.required' => 'กรุณากรอก Email หรือ Username',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
            ]);

            $user = User::where('email', $request->emailUsername)
                ->orWhere('username', $request->emailUsername)
                ->where('status_account', 'active')
                ->first();

            if ($user || !Hash::check($request->password, $user->password)) {
                if (!empty($user)) {

                    $logLogin = UserLogLogin::create([
                        'user_id' => $user->id,
                        'status' => "online",
                        'time_in' => now(),
                    ]);

                    if (!empty($logLogin)) {

                        $token = $user->createToken($user->username)->plainTextToken;

                        return response()->json([
                            'message' => "Login successfullry",
                            'userLogin' => $user,
                            'LogLogin' => $logLogin,
                            'token' => $token,
                        ], 200);
                    }
                }
            } else {
                return response()->json([
                    'message' => "Login false",
                    'userLogin' => $user
                ], 400);
            }

            return response()->json([
                'message' => "laravel function login response false"
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'login function error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {

            if (!$request->user()) {
                return response()->json([
                    'message' => 'controller logout() request false'
                ], 400);
            }

            $id = $request->user()->id;
            $user = User::findOrFail($id);

            $time_out = now();
            $time_in = $user->check_status_login->time_in;
            $total_login = $time_out->diffInSeconds($time_in);
            // $total_login = $time_out->diffInSeconds($time_in);
            // คำนวณเวลาทั้งหมดที่ล็อกอินเป็นวินาที (หรือแปลงเป็นนาที/ชั่วโมงก็ได้)

            $user->user_log_login()->create([
                'user_id' => $user->id,
                'status' => "offline",
                'time_out' => $time_out,
                'time_total_login' => $total_login,
            ]);

            if (empty($total_login)) {
                return response()->json([
                    'message' => 'logout() total time login null',
                ], 400);
            }

            $user->tokens()->delete();

            return response()->json([
                'message' => "logout() successfullry",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'logout function error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
