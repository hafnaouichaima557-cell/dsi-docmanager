<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'document.create',
            'document.update',
            'document.disable',
            'document.publish',
            'document.view',
            'user.create',
            'user.disable',
            'user.manage_roles',
            'audit.view',
            'profile.update',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Utilisateur — يخلق ويشوف فقط
        $user = Role::firstOrCreate(['name' => 'utilisateur']);
        $user->syncPermissions([
            'document.create',
            'document.view',
            'document.update',
            'profile.update',
        ]);

        // Responsable — يخلق وينشر
        $responsable = Role::firstOrCreate(['name' => 'responsable']);
        $responsable->syncPermissions([
            'document.create',
            'document.update',
            'document.disable',
            'document.publish',
            'document.view',
            'profile.update',
        ]);

        // Administrateur — كل الصلاحيات
        $admin = Role::firstOrCreate(['name' => 'administrateur']);
        $admin->syncPermissions(Permission::all());
    }
}



