<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Specialty;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardiologia = Specialty::where('name', 'Cardiologista')->first();

        $dermatologia = Specialty::where('name', 'Dermatologista')->first();

        $ortopedia = Specialty::where('name', 'Ortopedista')->first();

        $pediatria = Specialty::where('name', 'Pediatra')->first();

        $ginecologia = Specialty::where('name', 'Ginecologista')->first();

                Doctor::updateOrCreate(
            [ 
                'crm' => 'CRM/RJ 123456',
            ],
            [
                'specialty_id' => $cardiologia->id ,
                'name' => 'Dra. Ana Cordeiros',
                'phone' => '24999444444',
                'work_start_time' => '08:00:00' ,
                'work_end_time' => '13:00:00',
                'slot_duration' => '30',
                'is_active' => true,
            ],
                );
                Doctor::updateOrCreate(
            [ 
                'crm' => 'CRM/SP 654321',
            ],
            [
                'specialty_id' => $dermatologia->id ,
                'name' => 'Dr. Victor Carolos',
                'phone' => '24999555555',
                'work_start_time' => '08:00:00' ,
                'work_end_time' => '13:00:00',
                'slot_duration' => '30',
                'is_active' => true,
            ],
                );
                Doctor::updateOrCreate(
            [ 
                'crm' => 'CRM/RJ 145263',
            ],
            [
                'specialty_id' => $ortopedia->id ,
                'name' => 'Dr. Jones Manuel',
                'phone' => '24999666666',
                'work_start_time' => '13:00:00' ,
                'work_end_time' => '17:00:00',
                'slot_duration' => '30',
                'is_active' => true,
            ],
                );
                Doctor::updateOrCreate(
            [ 
                'crm' => 'CRM/SP 857649',
            ],
            [
                'specialty_id' => $pediatria->id ,
                'name' => 'Dr. Samuel pereira',
                'phone' => '24999777777',
                'work_start_time' => '08:00:00' ,
                'work_end_time' => '17:00:00',
                'slot_duration' => '40',
                'is_active' => true,
            ],
                );
                Doctor::updateOrCreate(
            [ 
                'crm' => 'CRM/RJ 856395',
            ],
            [
                'specialty_id' => $ginecologia->id ,
                'name' => 'Dra. Leidiane Almeida',
                'phone' => '24999985695',
                'work_start_time' => '10:00:00' ,
                'work_end_time' => '16:00:00',
                'slot_duration' => '60',
                'is_active' => true,
            ]
        );
    }
}
