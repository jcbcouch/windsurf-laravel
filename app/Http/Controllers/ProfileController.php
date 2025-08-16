<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile with their posts and videos.
     */
    public function index()
    {
        // Get the authenticated user with their posts and videos
        $user = Auth::user()->load(['posts', 'videos']);
        
        return view('profile.index', [
            'user' => $user,
            'posts' => $user->posts()->latest()->paginate(10)
        ]);
    }

    /**
     * Display the specified user's profile with their posts and videos.
     */
    public function show($id)
    {
        $user = User::with(['posts', 'videos'])->findOrFail($id);
        
        return view('profile.show', [
            'user' => $user,
            'posts' => $user->posts()->latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for editing the user's profile picture.
     */
    public function editPicture()
    {
        return view('profile.edit-picture', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        
        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store the new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        
        // Update user's avatar path
        $user->update([
            'avatar' => $avatarPath
        ]);

        return redirect()->route('profile.index')
            ->with('status', 'Profile picture updated successfully!');
    }

    /**
     * Remove the user's profile picture.
     */
    public function destroyPicture()
    {
        $user = auth()->user();
        
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            
            $user->update([
                'avatar' => null
            ]);
            
            return back()->with('status', 'Profile picture removed successfully!');
        }
        
        return back()->with('error', 'No profile picture to remove.');
    }

    /**
     * Show the form for editing the user's background color.
     */
    public function editBackground()
    {
        return view('profile.edit-background', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the user's background color.
     */
    public function updateBackground(Request $request)
    {
        $validated = $request->validate([
            'background_color' => 'required|string|size:7|starts_with:#|regex:/^#[a-f0-9]{6}$/i',
        ]);

        auth()->user()->update($validated);

        return redirect()
            ->route('profile.index')
            ->with('status', 'Background color updated successfully!');
    }
}
