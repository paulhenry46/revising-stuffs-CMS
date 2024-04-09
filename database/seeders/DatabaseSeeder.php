<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Course;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Create permissions
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage own posts']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'create posts']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage all posts']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'publish all posts']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'publish own posts']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage users']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage own comments']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage all comments']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage courses']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage levels']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'manage groups']);
        //Create roles and assigning the permissions
        Role::create(['guard_name' => 'sanctum', 'name' => 'admin'])->syncPermissions(['manage courses', 'manage levels', 'manage users', 'manage all posts', 'manage groups']);
        Role::create(['guard_name' => 'sanctum', 'name' => 'contributor'])->syncPermissions(['publish own posts']);
        Role::create(['guard_name' => 'sanctum', 'name' => 'moderator'])->syncPermissions(['publish all posts', 'manage all comments']);
        Role::create(['guard_name' => 'sanctum', 'name' => 'student'])->syncPermissions(['manage own posts', 'create posts', 'manage own comments']);
        Role::create(['guard_name' => 'sanctum', 'name' => 'system']);
        //Create Users
        //User 1 : Comment system user used when an guest user comment a post
        $user = new User;
        $user->name = 'Comment System';
        $user->email = 'comment@system.localhost';
        $user->password = Hash::make('pzuGdke85PUWQ420');
        $user->email_verified_at = now();
        $user->save();
        $user->syncRoles(['system','admin', 'student', 'contributor', 'moderator']);
        //User 2 : First Admin user
        $user = new User;
        $user->name = 'Admin';
        $user->email = 'admin@system.localhost';
        $user->password = Hash::make('d4d5ehdpdepd81');
        $user->email_verified_at = now();
        $user->save();
        $user->syncRoles(['admin', 'student', 'contributor', 'moderator']);
        
        //Create Course which stand for all courses
        $course = new Course;
        $course->name = 'All courses';
        $course->slug = 'all-courses';
        $course->color = 'red-500';
        $course->save();

        $this->call([
            GroupSeeder::class
        ]);
    }
}
