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
            // Método que se ejecuta cuando se corre la migración para crear la tabla 'products'
        Schema::create('products', function (Blueprint $table) {
            // Crea una columna llamada 'id' que es una clave primaria autoincrementable
            $table->id();

            // Crea una columna 'name' para almacenar el nombre del producto, con un tipo de dato string (varchar)
            $table->string('name');

            // Crea una columna 'description' para almacenar una descripción del producto, con un tipo de dato text (más largo que string)
            $table->text('description');

            // Crea una columna 'price' para almacenar el precio del producto. El tipo de dato decimal permite especificar números con decimales.
            // El primer parámetro (8) es la longitud total del número, y el segundo (2) es la cantidad de decimales.
            $table->decimal('price', 8, 2);

            // Crea una columna 'stock' para almacenar la cantidad de productos disponibles, de tipo integer (entero)
            $table->integer('stock');

            // Crea las columnas 'created_at' y 'updated_at' automáticamente para almacenar la fecha de creación y la última actualización del registro.
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
