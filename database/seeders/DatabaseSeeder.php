<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();

        User::updateOrCreate(
            [
                'name' => 'Administrador',
                'email' => 'admin@medischedule.test',
                'email_verified_at' => now(),
                'password' => Hash::make('admin12345678'),
                'cpf' => '12345678912',
                'phone' => '24999999999',
                'role' => 'admin',
            ],
            [
                'name' => 'Paciente Teste',
                'email' => 'patient@medischedule.test',
                'email_verified_at' => now(),
                'password' => Hash::make('patient12345678'),
                'cpf' => '24856257854',
                'phone' => '24999888888',
                'role' => 'patient',
            ],
            
        );
    
    }
}
