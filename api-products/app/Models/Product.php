<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Modelo que representa un producto en la base de datos
class Product extends Model
{
    // Usando la característica de fábrica para generar instancias del modelo de manera sencilla
    use HasFactory;

    // Los atributos que se pueden asignar masivamente, es decir, aquellos que pueden ser modificados mediante métodos como create() o update()
    protected $fillable = ['name', 'description', 'price', 'stock'];
}
