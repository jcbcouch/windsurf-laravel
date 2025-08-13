<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a paginated listing of users with their roles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $allRoles = Role::all();
        
        return view('admin.users.index', compact('users', 'allRoles'));
    }

    /**
     * Assign a role to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);
        $user->roles()->syncWithoutDetaching([$request->role_id]);

        return back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Remove a role from a user.
     *
     * @param  int  $userId
     * @param  int  $roleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeRole($userId, $roleId)
    {
        $user = User::findOrFail($userId);
        $user->roles()->detach($roleId);

        return back()->with('success', 'Role removed successfully.');
    }
}
