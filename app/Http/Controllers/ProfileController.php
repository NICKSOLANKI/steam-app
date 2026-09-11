<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    // Show profile page
    public function show()
    {
        $user = Auth::user();
        return view('front.profile', compact('user'));
    }

    // Update username
    public function updateUsername(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        $user       = Auth::user();
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'success' => true,
            'name'    => $user->name,
            'message' => 'Username updated successfully.'
        ]);
    }

    // Update avatar
    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old file if exists
            $this->deleteOld($user->avatar);

            // Store new file
            $filename = 'avatar_' . $user->id . '.' . $request->file('avatar')->extension();
            $path     = $request->file('avatar')->storeAs('avatars', $filename, 'public');

            // ✅ Use Storage::url for proper /storage/... link
            $user->avatar = Storage::url($path);
            $user->save();
        }

        return response()->json([
            'success'    => true,
            'avatar_url' => $user->avatar,
            'message'    => 'Avatar updated successfully.',
        ]);
    }

    // helper method for deleting
    private function deleteOld($path)
    {
        if ($path) {
            $clean = str_replace('/storage/', '', $path);
            if (Storage::disk('public')->exists($clean)) {
                Storage::disk('public')->delete($clean);
            }
        }
    }

    // Update background
    public function updateBackground(Request $request)
    {
        try {
            $request->validate([
                'background' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        $user = Auth::user();

        if ($request->hasFile('background')) {
            // Delete old bg if exists
            $this->deleteOld($user->profile_bg);

            $path             = $request->file('background')->store('profile_images', 'public');
            $user->profile_bg = Storage::url($path);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'bg_url'  => $user->profile_bg,
            'message' => 'Background updated successfully.'
        ]);
    }

    // Update mini-profile
    public function updateMiniProfile(Request $request)
    {
        try {
            $request->validate([
                'mini_profile' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        $user = Auth::user();

        if ($request->hasFile('mini_profile')) {
            $this->deleteOld($user->mini_profile);

            $path               = $request->file('mini_profile')->store('mini_profiles', 'public');
            $user->mini_profile = Storage::url($path);
            $user->save();
        }

        return response()->json([
            'success'          => true,
            'mini_profile_url' => $user->mini_profile,
            'message'          => 'Mini profile updated successfully.'
        ]);
    }
}