<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\Course;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class RSCMS_UpdateToV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rscms:updateToV2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update from a 1.x version of the app to the 2.0 version. Run once';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    Permission::create(['guard_name' => 'sanctum', 'name' => 'manage groups']);
    $role = Role::findByName('admin', 'sanctum');
    $role->syncPermissions(['manage courses', 'manage levels', 'manage users', 'manage all posts', 'manage groups']);
    $this->info('The command was successful!');
    }
}
