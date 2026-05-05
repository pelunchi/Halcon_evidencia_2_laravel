<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrador',
                'username' => 'admin',
                'email'    => 'admin@halcon.com',
                'password' => Hash::make('admin123'),
                'role'     => 'Admin',
                'active'   => true,
            ],
            [
                'name'     => 'Carlos Mendoza',
                'username' => 'cmendoza',
                'email'    => 'cmendoza@halcon.com',
                'password' => Hash::make('ventas123'),
                'role'     => 'Ventas',
                'active'   => true,
            ],
            [
                'name'     => 'Lupita Ramírez',
                'username' => 'lramirez',
                'email'    => 'lramirez@halcon.com',
                'password' => Hash::make('alma123'),
                'role'     => 'Almacen',
                'active'   => true,
            ],
            [
                'name'     => 'Jorge Soto',
                'username' => 'jsoto',
                'email'    => 'jsoto@halcon.com',
                'password' => Hash::make('compras123'),
                'role'     => 'Compras',
                'active'   => true,
            ],
            [
                'name'     => 'Miguel Ángel Torres',
                'username' => 'matorres',
                'email'    => 'matorres@halcon.com',
                'password' => Hash::make('ruta123'),
                'role'     => 'Ruta',
                'active'   => true,
            ],
            [
                'name'     => 'Sandra Villalba',
                'username' => 'svilalba',
                'email'    => 'svilalba@halcon.com',
                'password' => Hash::make('ventas123'),
                'role'     => 'Ventas',
                'active'   => false,  // inactive user for testing
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['username' => $userData['username']], $userData);
        }
    }
}
