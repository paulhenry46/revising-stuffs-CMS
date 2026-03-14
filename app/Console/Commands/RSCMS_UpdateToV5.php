<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RSCMS_UpdateToV5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rscms:updateToV5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update from a 4.x version of the app to the 5.0 version. Run once. Updates co-admin permissions: removes manage levels, adds manage co-admin courses.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create the new co-admin specific permission if it doesn't exist
        if (!Permission::where('name', 'manage co-admin courses')->where('guard_name', 'sanctum')->exists()) {
            Permission::create(['guard_name' => 'sanctum', 'name' => 'manage co-admin courses']);
            $this->info('Created permission: manage co-admin courses');
        }

        // Update co-admin role: remove manage levels, add manage co-admin courses
        $coAdminRole = Role::findByName('co-admin', 'sanctum');
        $coAdminRole->syncPermissions([
            'manage co-admin courses',
            'manage users',
            'manage all posts',
            'publish all posts',
            'manage all comments',
        ]);

        $this->info('Updated co-admin permissions: removed manage courses, manage levels; added manage co-admin courses.');
        $this->info('The command was successful!');
    }
}
