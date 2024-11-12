<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions for User
        $userPermissions = [
            'view device data',         // Melihat Data Perangkat
            'search device data',       // Mencari Data Perangkat
            'edit device data',         // Mengubah Data Perangkat
            'generate reports',         // Menghasilkan Laporan
            'edit account',             // Mengubah Akun
            'modify device count',      // Mengubah jumlah perangkat (SFP)
        ];

        // Define permissions for Admin
        $adminPermissions = [
            'add category',             // Menambah Kategori
            'add device data',          // Menambah Data Perangkat
            'delete device data',       // Menghapus Data Perangkat
            'create account',           // Membuat Akun
            'delete account',           // Menghapus Akun
            'manage data access',       // Mengatur Izin Akses Data
        ];

        // Create and assign each permission for User and Admin
        foreach (array_merge($userPermissions, $adminPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'username' => 'Admin',
                'name' => 'Admin User',
                'password' => Hash::make('password123'), // Change the password after seeding
            ]
        );

        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        foreach (array_merge($userPermissions, $adminPermissions) as $permission) {
            if (!$adminUser->hasPermissionTo($permission)) {
                $adminUser->givePermissionTo($permission);
            }
        }

        // Output a message to confirm the admin user creation
        $this->command->info('Admin user created with permissions successfully.');
    }
}
