<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specialties')->upsert(
            [
                [
                    'name' => 'Cardiologista',
                    'description' => 'Médico especializado em diagnosticar, tratar e prevenir doenças do coração e do sistema circulatório, atuando em consultórios, clínicas e hospitais.',
                ],
                [
                    'name' => 'Dermatologista',
                    'description' => 'Médico especializado no diagnóstico, tratamento e prevenção das doenças da pele, cabelos, unhas e mucosas.',
                ],
                [
                    'name' => 'Ortopedista',
                    'description' => 'Médico responsável pelo diagnóstico, tratamento e prevenção de doenças e lesões que afetam o sistema musculoesquelético, incluindo ossos, músculos, ligamentos e articulações.',
                ],
                [
                    'name' => 'Pediatra',
                    'description' => 'Médico especializado no cuidado da saúde, crescimento e desenvolvimento de bebês, crianças e adolescentes.',
                ],
                [
                    'name' => 'Ginecologista',
                    'description' => 'Médico especializado na saúde do sistema reprodutor feminino, abrangendo prevenção, diagnóstico e tratamento de condições que afetam mulheres em todas as fases da vida.',
                ],
            ],
            ['name'],
            ['description']
        );
    }
}
