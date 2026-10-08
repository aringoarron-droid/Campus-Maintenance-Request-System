<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users (READ)
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    // Show form to create user (CREATE)
    public function create()
    {
        // Replace array values with exact roles from your Project Proposal
        $roles = ['Admin', 'Manager', 'Staff', 'Customer'];

        return view('users.create', compact('roles'));
    }

    // Save new user (CREATE)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show form to edit user (UPDATE)
    public function edit(User $user)
    {
        $roles = ['Admin', 'Manager', 'Staff', 'Customer'];

        return view('users.edit', compact('user', 'roles'));
    }

    // Update user details (UPDATE)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete user (DELETE)
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
