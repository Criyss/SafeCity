<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->enum('departamento', [
                'La Paz', 'Cochabamba', 'Santa Cruz', 'Oruro',
                'Potosí', 'Chuquisaca', 'Tarija', 'Beni', 'Pando'
            ])->after('categoria_id');
            $table->enum('gravedad', ['leve', 'moderada', 'grave'])->default('leve')->after('longitud');
            $table->enum('estado', ['pendiente', 'en_proceso', 'resuelto', 'rechazado'])->default('pendiente')->after('gravedad');
            $table->date('fecha_incidente')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropColumn(['departamento', 'gravedad', 'estado', 'fecha_incidente']);
        });
    }
};
