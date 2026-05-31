<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        $permissions = [
            // Product permissions
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'manage-stock',

            // Order permissions
            'view-orders',
            'view-all-orders',
            'create-orders',
            'edit-orders',
            'cancel-orders',
            'update-order-status',

            // User permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'assign-roles',

            // Category permissions
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',

            // Admin permissions
            'access-admin-dashboard',
            'view-reports',
            'manage-roles',
            'manage-permissions',
            'view-logs',
            'view-backups',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // Assign permissions to roles

        // Super Admin: all permissions
        $superAdminRole->givePermissionTo($permissions);

        // Admin: almost all permissions except role/permission management (only super-admin)
        $adminPermissions = array_diff($permissions, [
            'manage-roles',
            'manage-permissions',
        ]);
        $adminRole->givePermissionTo($adminPermissions);

        // Manager: limited permissions
        $managerPermissions = [
            'view-products',
            'create-products',
            'edit-products',
            'manage-stock',
            'view-orders',
            'view-all-orders',
            'update-order-status',
            'view-categories',
            'view-reports',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Customer: view-only permissions
        $customerPermissions = [
            'view-products',
            'view-categories',
            'view-orders',
            'create-orders',
            'cancel-orders',
        ];
        $customerRole->givePermissionTo($customerPermissions);
    }
}
