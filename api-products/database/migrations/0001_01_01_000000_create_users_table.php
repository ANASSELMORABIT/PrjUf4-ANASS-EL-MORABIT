<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migración para crear la tabla 'users' en la base de datos
        Schema::create('users', function (Blueprint $table) {
            // Crear una columna de tipo ID que será la clave primaria autoincremental
            $table->id();
            
            // Crear una columna para almacenar el nombre del usuario
            $table->string('name');
            
            // Crear una columna para almacenar el correo electrónico del usuario
            // La columna 'email' debe ser única, por lo que no puede haber dos usuarios con el mismo correo electrónico
            $table->string('email')->unique();
            
            // Crear una columna para almacenar la fecha y hora de verificación del correo electrónico
            // Esta columna puede ser nula si el usuario no ha verificado su correo electrónico
            $table->timestamp('email_verified_at')->nullable();
            
            // Crear una columna para almacenar la contraseña del usuario
            $table->string('password');
            
            // Crear una columna para almacenar el token de "remember me" (recordar sesión)
            $table->rememberToken();
            
            // Agregar columnas para las marcas de tiempo de creación y actualización (created_at, updated_at)
            $table->timestamps();
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
