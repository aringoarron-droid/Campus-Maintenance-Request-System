<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Show all users (Read)
    public function index()
    {

        $users = User::all();

        return view('users.index', compact('users'));
    }

    // Show the "add user" form
    public function create()
    {
        return view('users.create');
    }

    // Save a new user (Create)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,staff,requester',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show the "edit user" form
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    // Save changes to a user (Update)
    public function update(Request $request, string $id)
    {
        // Changed $users to $user here to match line 59
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id, // Updated here too
            'role' => 'required|in:admin,staff,requester',
        ]);

        // This line will now work perfectly
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
