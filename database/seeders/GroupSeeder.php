<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Group;
use App\Models\User;

class GroupSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        $group = new Group;
        $group->name = 'private';
        $group->save();

        $group = new Group;
        $group->name = 'all';
        $group->save();
    }
}
