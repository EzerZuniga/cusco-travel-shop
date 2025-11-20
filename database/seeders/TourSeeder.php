<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Ejecutar los seeders de la base de datoss
     */
    public function run(): void
    {
        $tours = [
            [
                'titulo' => 'Machu Picchu Full Day',
                'slug' => Str::slug('Machu Picchu Full Day'),
                'descripcion' => 'Descubre la ciudadela inca más famosa del mundo en un día completo.',
                'precio' => 350.00,
                'duracion' => '1 día',
                'activo' => true,
            ],
            [
                'titulo' => 'Valle Sagrado de los Incas',
                'slug' => Str::slug('Valle Sagrado de los Incas'),
                'descripcion' => 'Explora el valle sagrado con sus mercados tradicionales y sitios arqueológicos.',
                'precio' => 120.00,
                'duracion' => '1 día',
                'activo' => true,
            ],
            [
                'titulo' => 'Montaña de 7 Colores (Vinicunca)',
                'slug' => Str::slug('Montaña de 7 Colores'),
                'descripcion' => 'Visita la impresionante montaña de colores en una aventura de altura.',
                'precio' => 180.00,
                'duracion' => '1 día',
                'activo' => true,
            ],
            [
                'titulo' => 'Ciudad de Cusco y Sacsayhuamán',
                'slug' => Str::slug('Ciudad de Cusco y Sacsayhuamán'),
                'descripcion' => 'Recorrido por la ciudad imperial y la fortaleza de Sacsayhuamán.',
                'precio' => 80.00,
                'duracion' => 'Medio día',
                'activo' => true,
            ],
            [
                'titulo' => 'Laguna Humantay',
                'slug' => Str::slug('Laguna Humantay'),
                'descripcion' => 'Hermosa laguna turquesa en las alturas de los Andes.',
                'precio' => 150.00,
                'duracion' => '1 día',
                'activo' => true,
            ],
        ];

        foreach ($tours as $tour) {
            Tour::create($tour);
        }
    }
}
