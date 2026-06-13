<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Robo',                    'descripcion' => 'Sustracción de bienes o pertenencias'],
            ['nombre' => 'Vandalismo',              'descripcion' => 'Daños a la propiedad pública o privada'],
            ['nombre' => 'Accidente de tránsito',   'descripcion' => 'Colisiones o accidentes en vías públicas'],
            ['nombre' => 'Bache',                   'descripcion' => 'Hoyos o deterioro en el pavimento de calles'],
            ['nombre' => 'Semáforo dañado',         'descripcion' => 'Semáforos en mal estado o sin funcionamiento'],
            ['nombre' => 'Basura',                  'descripcion' => 'Acumulación de residuos en espacios públicos'],
            ['nombre' => 'Inundación',              'descripcion' => 'Zonas anegadas por lluvias o desborde de ríos'],
            ['nombre' => 'Alumbrado defectuoso',    'descripcion' => 'Postes o luminarias sin funcionamiento'],
            ['nombre' => 'Fuga de agua',            'descripcion' => 'Pérdidas de agua en tuberías o cañerías públicas'],
            ['nombre' => 'Construcción ilegal',     'descripcion' => 'Edificaciones sin permisos o en zonas prohibidas'],
            ['nombre' => 'Vehículo mal estacionado','descripcion' => 'Autos obstruyendo vías o zonas prohibidas'],
            ['nombre' => 'Contaminación',           'descripcion' => 'Contaminación ambiental, sonora o visual'],
            ['nombre' => 'Ruido excesivo',          'descripcion' => 'Niveles de ruido que afectan a los vecinos'],
            ['nombre' => 'Zona insegura',           'descripcion' => 'Áreas con alta incidencia delictiva'],
            ['nombre' => 'Emergencia',              'descripcion' => 'Situaciones que requieren atención inmediata'],
            ['nombre' => 'Otros',                   'descripcion' => 'Incidencias que no encajan en otras categorías'],
        ];

        foreach ($categorias as $cat) {
            Categoria::create($cat);
        }
    }
}