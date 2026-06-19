<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Robo', 'Vandalismo', 'Accidente de tránsito', 'Bache',
            'Semáforo dañado', 'Basura', 'Inundación', 'Alumbrado defectuoso',
            'Fuga de agua', 'Construcción ilegal', 'Vehículo mal estacionado',
            'Contaminación', 'Ruido excesivo', 'Zona insegura', 'Emergencia', 'Otros',
        ];

        foreach ($categorias as $nombre) {
            Categoria::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
