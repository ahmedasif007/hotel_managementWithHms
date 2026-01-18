<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $admin = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator']);
        $receptionist = Role::firstOrCreate(['name' => 'receptionist'], ['description' => 'Receptionist']);
        $staff = Role::firstOrCreate(['name' => 'staff'], ['description' => 'Hotel Staff']);

        // Create Permissions
        $permissions = [
            'view_rooms',
            'create_room',
            'edit_room',
            'delete_room',
            'view_guests',
            'create_guest',
            'edit_guest',
            'delete_guest',
            'view_reservations',
            'create_reservation',
            'edit_reservation',
            'cancel_reservation',
            'check_in',
            'check_out',
            'view_invoices',
            'create_invoice',
            'view_payments',
            'record_payment',
            'view_reports',
            'manage_users',
            'manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign Permissions to Admin
        $admin->permissions()->syncWithoutDetaching(Permission::all()->pluck('id'));

        // Assign Permissions to Receptionist
        $receptionistPermissions = Permission::whereIn('name', [
            'view_rooms',
            'view_guests',
            'create_guest',
            'edit_guest',
            'view_reservations',
            'create_reservation',
            'edit_reservation',
            'check_in',
            'check_out',
            'view_invoices',
            'create_invoice',
            'view_payments',
            'record_payment',
        ])->pluck('id');
        $receptionist->permissions()->syncWithoutDetaching($receptionistPermissions);

        // Assign Permissions to Staff
        $staffPermissions = Permission::whereIn('name', [
            'view_rooms',
            'view_guests',
            'view_reservations',
            'check_in',
            'check_out',
        ])->pluck('id');
        $staff->permissions()->syncWithoutDetaching($staffPermissions);

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@hotel.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => $admin->id,
                'is_active' => true,
            ]
        );

        // Create Receptionist User
        User::firstOrCreate(
            ['email' => 'receptionist@hotel.local'],
            [
                'name' => 'Receptionist',
                'password' => Hash::make('password'),
                'role_id' => $receptionist->id,
                'is_active' => true,
            ]
        );

        // Create Staff User
        User::firstOrCreate(
            ['email' => 'staff@hotel.local'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password'),
                'role_id' => $staff->id,
                'is_active' => true,
            ]
        );
    }
}
