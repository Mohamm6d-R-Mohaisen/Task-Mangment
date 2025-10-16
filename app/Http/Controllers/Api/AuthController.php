<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;

class AuthController extends Controller
{
    //
      // تسجيل الدخول موحد (admin/user)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // تحقق أولاً في جدول الـ Admin
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('admin_token', ['*'], now()->addDays(7))->plainTextToken;
            return response()->json([
                'status' => true,
                'type' => 'admin',
                'message' => 'Admin login successful',
                'token' => $token
            ]);
        }

        // تحقق في جدول المستخدمين العاديين
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('user_token', ['*'], now()->addDays(7))->plainTextToken;
            return response()->json([
                'status' => true,
                'type' => 'user',
                'message' => 'User login successful',
                'token' => $token
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['Invalid credentials.'],
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $request->user()
        ]);
    }
}
