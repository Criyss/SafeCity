<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Basura y Residuos',           'descripcion' => 'Acumulación de basura, residuos o mal manejo de desechos en vía pública'],
            ['nombre' => 'Alumbrado Público',            'descripcion' => 'Fallas en el alumbrado de calles, parques o espacios públicos'],
            ['nombre' => 'Baches y Pavimento',           'descripcion' => 'Deterioro de calzadas, baches, grietas o daños en el asfalto'],
            ['nombre' => 'Vandalismo',                   'descripcion' => 'Daños intencionales a mobiliario urbano, paredes o propiedad pública'],
            ['nombre' => 'Robos y Seguridad',            'descripcion' => 'Incidentes de robo, asalto o situaciones de inseguridad ciudadana'],
            ['nombre' => 'Accidentes de Tránsito',      'descripcion' => 'Colisiones, atropellos u otros accidentes en la vía pública'],
            ['nombre' => 'Semáforos y Señales',         'descripcion' => 'Semáforos dañados, señales de tráfico inexistentes o en mal estado'],
            ['nombre' => 'Animales en Vía Pública',     'descripcion' => 'Animales abandonados, peligrosos o que representen un riesgo vial'],
            ['nombre' => 'Inundaciones',                 'descripcion' => 'Acumulación de agua, desborde de canales o calles inundadas'],
            ['nombre' => 'Deslizamientos',               'descripcion' => 'Derrumbes, erosión de taludes o movimiento de tierra que afecte vías'],
            ['nombre' => 'Contaminación Ambiental',     'descripcion' => 'Emisiones tóxicas, vertido de residuos contaminantes o polución'],
            ['nombre' => 'Ruido y Contaminación Acústica', 'descripcion' => 'Niveles de ruido excesivos en zonas residenciales o comerciales'],
            ['nombre' => 'Servicios Básicos',            'descripcion' => 'Fallas en agua potable, alcantarillado, gas u otros servicios básicos'],
            ['nombre' => 'Infraestructura Pública',     'descripcion' => 'Daños en puentes, aceras, muros de contención u obras públicas'],
            ['nombre' => 'Parques y Espacios Públicos', 'descripcion' => 'Deterioro de áreas verdes, juegos infantiles o plazas públicas'],
            ['nombre' => 'Otros',                        'descripcion' => 'Incidencias que no encajan en las categorías anteriores'],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }
    }
}
