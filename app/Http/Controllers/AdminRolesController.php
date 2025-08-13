<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminRolesController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('roles', 'name')
            ],
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Generate a slug from the name if not provided
        $validated['slug'] = strtolower(str_replace(' ', '-', $validated['name']));
        
        // Set display_name to name if not provided
        if (empty($validated['display_name'])) {
            $validated['display_name'] = ucwords(str_replace(['_', '-'], ' ', $validated['name']));
        }

        Role::create($validated);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('roles', 'name')->ignore($role->id)
            ],
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Generate a slug from the name if changed
        if ($role->name !== $validated['name']) {
            $validated['slug'] = strtolower(str_replace(' ', '-', $validated['name']));
        }
        
        // Set display_name to name if not provided
        if (empty($validated['display_name'])) {
            $validated['display_name'] = ucwords(str_replace(['_', '-'], ' ', $validated['name']));
        }

        $role->update($validated);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deletion if role is assigned to users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role that is assigned to users.');
        }
        
        $role->delete();
        
        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
