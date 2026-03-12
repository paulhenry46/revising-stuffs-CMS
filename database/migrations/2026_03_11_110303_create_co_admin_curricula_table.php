<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('co_admin_curricula', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('curriculum_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'curriculum_id']);
        });

        if (!Role::where('name', 'co-admin')->exists()) {
            Role::create(['guard_name' => 'sanctum', 'name' => 'co-admin'])->syncPermissions(['manage courses', 'manage levels', 'manage users', 'manage all posts', 'publish all posts', 'manage all comments']);
            }
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {

                Permission::firstOrCreate(['name' => 'manage site', 'guard_name' => 'sanctum']);
                $adminRole->givePermissionTo('manage site');

            }
        }

            

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('co_admin_curricula');
    }
};
