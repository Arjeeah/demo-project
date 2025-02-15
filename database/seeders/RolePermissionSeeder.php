<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions.
        $permissions = [
            'create event',
            'edit event',
            'delete event',
            'view event',
            'create venue',
            'edit venue',
            'delete venue',
            'view venue',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create roles and assign permissions.

        // Admin gets all permissions.
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Event Manager role.
        $eventManager = Role::firstOrCreate(['name' => 'event_manager']);
        $eventManager->syncPermissions([
            'create event',
            'edit event',
            'delete event',
            'view event',
            'create venue',
            'edit venue',
            'delete venue',
            'view venue'
        ]);

        // Attendee role.
        $attendee = Role::firstOrCreate(['name' => 'attendee']);
        $attendee->syncPermissions(['view event']);

        // assign the admin role to the first user.
        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
