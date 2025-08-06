<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest; //
use App\Http\Requests\UpdateUserRequest; //
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function register(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'email' => $request->email,

            ]);
            mail::to($user->email)->send(new WelcomeMail($user));
            return response()->json([
                'message' => 'Thank You For sign',
                'user' => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }


    function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return response()->json(['message' => 'Invalid Data'], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }

    function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'logout successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function update(UpdateUserRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return response()->json($user, 200);
    }
    function show()
    {
        $user = Auth::user()->load('profile');
        return new UserResource($user);
    }
    function destroy()
    {
        $user = Auth::user();
        $user->delete();
        return response()->json('deleted', 200);
    }
    function getallusers() //admins
    {
        $users = User::with('profile')->get(); // أفضل من all()->load()
        return UserResource::collection($users);
    }
    function getprofile()
    {
        $profile = Auth::user()->profile;
        return response()->json($profile, 200);
    }
    function gettasks()
    {
        $tasks = Auth::user()->task;
        return response()->json($tasks, 200);
    }
}
