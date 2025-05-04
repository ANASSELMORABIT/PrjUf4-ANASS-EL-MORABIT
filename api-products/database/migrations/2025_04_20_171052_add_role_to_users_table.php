<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        // Método que se ejecuta cuando se corre la migración para modificar la tabla 'users'
        public function up(): void
        {
            // Modificar la tabla 'users'
            Schema::table('users', function (Blueprint $table) {
                // Añadir una nueva columna 'role' que almacenará el rol del usuario
                // Se utiliza un tipo de datos ENUM para restringir los valores posibles a 'admin' o 'user'
                // Se establece 'user' como valor por defecto
                $table->enum('role', ['admin', 'user'])->default('user');
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user');
        });
    }
};
