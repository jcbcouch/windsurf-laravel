<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile with their posts.
     */
    public function index()
    {
        // Get the authenticated user with their posts
        $user = Auth::user()->load('posts');
        
        return view('profile.index', [
            'user' => $user,
            'posts' => $user->posts()->latest()->paginate(10)
        ]);
    }
}
