<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->string('departamento')->default('La Paz')->after('estado');
            $table->enum('gravedad', ['Baja', 'Media', 'Alta', 'Crítica'])->default('Media')->after('departamento');
        });
    }

    public function down(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropColumn(['departamento', 'gravedad']);
        });
    }
};
