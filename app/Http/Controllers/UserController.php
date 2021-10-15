<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')
            ->filter($request->only('search', 'trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'created_at' => $user->created_at,
                    'deleted_at' => $user->deleted_at,
                    'role' => $user->role,
                ];
            });

        return Inertia::render('Admin/ControlPanel/Index', [
            'users' => $users,
            'filters' => $request->only('search', 'trashed'),
            'can' => [
                'create_user' => Auth::user()->can('admin_actions'),
                'edit_user' => Auth::user()->can('admin_actions'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/ControlPanel/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'integer'
        ]);

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->remember_token = Str::random(60);

        $user->save();

        event(new Registered($user));

        return Redirect::route('admin.control-panel.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/ControlPanel/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['confirmed', Rules\Password::defaults()],
            'role' => 'integer'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password) {
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(60);
        }
        $user->role = $request->role;

        $user->save();

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
