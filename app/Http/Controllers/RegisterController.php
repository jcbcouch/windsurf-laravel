<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // @desc    Show register form
    // @route   GET /register
    public function register(): View
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    // @desc    Store user in database
    // @route   POST /register
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        // Assign the selected role to the user
        $role = Role::findOrFail($validatedData['role_id']);
        $user->roles()->attach($role->id);

        return redirect()->route('login')
            ->with('success', 'You have been registered successfully! Please log in.');
    }
}