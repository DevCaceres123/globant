<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega el color identificador y la descripción del rol a la tabla
     * `roles` de Spatie. El nombre de la tabla se lee de la config de
     * spatie/laravel-permission.
     */
    public function up(): void
    {
        $tabla = config('permission.table_names.roles', 'roles');

        Schema::table($tabla, function (Blueprint $table) {
            // Color hex del rol (#7b2233). Nullable para roles ya existentes.
            $table->string('color', 20)->nullable()->after('name');
            // Descripción corta del rol. Nullable.
            $table->string('descripcion', 255)->nullable()->after('color');
        });
    }

    public function down(): void
    {
        $tabla = config('permission.table_names.roles', 'roles');

        Schema::table($tabla, function (Blueprint $table) {
            $table->dropColumn(['color', 'descripcion']);
        });
    }
};
