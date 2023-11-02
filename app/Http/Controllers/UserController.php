<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $users = User::all();
        return view('users.show')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User;
        $user->id = 0;
        return view('users.edit', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    if($request->role == 'admin'){
    $user->syncRoles(['admin', 'student', 'contributor', 'moderator']);
    }
    if($request->role == 'moderator'){
    $user->syncRoles(['student', 'contributor', 'moderator']);
    }
    if($request->role == 'contributor'){
    $user->syncRoles(['student', 'contributor',]);
    }
    if($request->role == 'student'){
    $user->syncRoles(['student']);
    }

    return redirect()->route('users.index')->with('message', __('The user has been created.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
    $user->name = $request->name;
    $user->save();

    if($request->role == 'admin'){
    $user->syncRoles(['admin', 'student', 'contributor', 'moderator']);
    }
    if($request->role == 'moderator'){
    $user->syncRoles(['student', 'contributor', 'moderator']);
    }
    if($request->role == 'contributor'){
    $user->syncRoles(['student', 'contributor',]);
    }
    if($request->role == 'student'){
    $user->syncRoles(['student']);
    }
    return redirect()->route('users.index')->with('message', __('The user has been modified.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {   
            $user->delete();
            return redirect()->route('users.index')->with('message', __('The user has been deleted.'));

    }
}
