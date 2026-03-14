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
    // 1. Vider le cache AVANT toute opération
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    $permissionNames = [
        'manage co-admin courses',
        'manage users',
        'manage all posts',
        'publish all posts',
        'manage all comments',
        'manage site' // Ajouté ici pour plus de sécurité
    ];

    // 2. Créer toutes les permissions et les stocker dans une collection
    $permissions = collect($permissionNames)->map(function ($name) {
        return Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => 'sanctum',
        ]);
    });

    $this->info('Permissions existantes ou créées.');

    // 3. Récupérer ou créer le rôle co-admin
    $coAdminRole = Role::firstOrCreate([
        'name' => 'co-admin',
        'guard_name' => 'sanctum',
    ]);

    // On synchronise en passant la collection d'objets (plus robuste que les noms)
    $coAdminRole->syncPermissions($permissions->whereIn('name', [
        'manage co-admin courses',
        'manage users',
        'manage all posts',
        'publish all posts',
        'manage all comments',
    ]));

    $this->info('Permissions synchronisées pour le rôle co-admin.');

    // 4. Update admin role
    $adminRole = Role::where('name', 'admin')
                     ->where('guard_name', 'sanctum')
                     ->first();

    if ($adminRole) {
        $adminRole->givePermissionTo('manage site');
        $this->info('Permission manage site ajoutée à admin.');
    }

    // 5. Vider le cache APRÈS pour que l'app soit prête immédiatement
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    $this->info('La commande a été exécutée avec succès !');
}
}
