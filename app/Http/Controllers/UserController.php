<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Curriculum;
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
    {
        $authUser = auth()->user();
        if ($authUser->hasRole('co-admin') && !$authUser->hasRole('admin')) {
            $curriculaIds = $authUser->getManagedCurriculaIds();
            $users = User::where('id', '!=', 1)
            ->whereIn('curriculum_id', $curriculaIds)
            ->whereDoesntHave('roles', function($query) {
                $query->whereIn('name', ['admin', 'moderator', 'co-admin']);
            })
            ->get();
        } else {
            $users = User::where('id', '!=', 1)->get();
        }
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User;
        $user->id = 0;
        $curricula = Curriculum::all();
        return view('users.edit', compact('user', 'curricula'));
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
    $authUser = auth()->user();

     if ($authUser->hasRole('co-admin') && !$authUser->hasRole('admin')) {
        if($request->role == 'contributor'){
        $user->syncRoles(['student', 'contributor']);
        }
        if($request->role == 'student'){
        $user->syncRoles(['student']);
        }
    } else {
        if($request->role == 'admin'){
        $user->syncRoles(['admin', 'student', 'contributor', 'moderator']);
        }
        if($request->role == 'co-admin'){
        $user->syncRoles(['co-admin', 'student', 'contributor']);
        $user->managedCurricula()->sync($request->input('curricula', []));
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
    }

    return redirect()->route('users.index')->with('message', __('The user has been created.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole('co-admin') && !$authUser->hasRole('admin')) {
            $curriculaIds = $authUser->getManagedCurriculaIds();
            abort_if(
                !in_array($user->curriculum_id, $curriculaIds) || 
                $user->hasAnyRole(['admin', 'moderator', 'co-admin']),
                403
            );
        }

        $curricula = Curriculum::all();
        return view('users.edit', compact('user', 'curricula'));
    }

    public function cursusForm()
    {
        $user = auth()->user();
        return view('profile.update-cursus', compact('user'));
    }

    public function cursusUpdate(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'curriculum_id' => 'required|exists:curricula,id',
            'school_id' => 'required|exists:schools,id',
            'course_*' => 'required|exists:courses,id',
        ]);

        $user->school_id = $request->school_id;
        $user->curriculum_id = $request->curriculum_id;
        $user->level_id = $request->level_id;
        $user->courses_id = [0]; //Reinitialise the value before updating
        foreach($request->all() as $key => $value) {
  
            if(str_starts_with($key, 'course_')){
                $value = intval($value);
                $new_array = array_merge((array)$value, $user->courses_id);
                $user->courses_id = $new_array;
            }
        
        }
        $user->save();
        
        return redirect()->route('dashboard')->with('message', __('You have updated your cursus informations.'));
        
    }

    /**
     * Update the specified user.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
    $authUser = auth()->user();
    $user->name = $request->name;
    $user->save();

    // Co-admins can only promote users to contributor or student
    if ($authUser->hasRole('co-admin') && !$authUser->hasRole('admin')) {
        if($request->role == 'contributor'){
        $user->syncRoles(['student', 'contributor']);
        }
        if($request->role == 'student'){
        $user->syncRoles(['student']);
        }
    } else {
        if($request->role == 'admin'){
        $user->syncRoles(['admin', 'student', 'contributor', 'moderator']);
        }
        if($request->role == 'co-admin'){
        $user->syncRoles(['co-admin', 'student', 'contributor']);
        $user->managedCurricula()->sync($request->input('curricula', []));
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
