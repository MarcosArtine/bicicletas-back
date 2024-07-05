<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    use HasFactory;
    protected $table = "bicicletas";
    protected $fillable = ["marca", "modelo", "precio_por_hora", "foto_url"];
}
