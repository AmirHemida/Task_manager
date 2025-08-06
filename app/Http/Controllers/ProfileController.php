<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    function index()
    {
        $profile = Auth::user()->profile;
        return new ProfileResource($profile);
    }
    function store(StoreProfileRequest $request)
    {
        try {
            if (Auth::user()->profile) {
                return response()->json(['message' => 'Profile already exists'], 409);
            }
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('myphoto', 'public');
                $data['image'] = $path;
            }
            $profile = Profile::create($data);
            return response()->json($profile, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function update(UpdateProfileRequest $request)
    {
        try {
            $profile = Auth::user()->profile;
            if (!$profile) {
                return response()->json(["message" => "Not Found"], 404);
            }
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('myphoto', 'public');
                $data['image'] = $path;
            }
            $profile->update($data);
            return response()->json($profile, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }

    function destroy()
    {
        $profile = Auth::user()->profile;
        $profile->delete();
        return response()->json("Profile Deleted Suc", 204);
    }
    function getallprofiles() //admins
    {
        return response()->json(Profile::all(), 200);
    }
}
