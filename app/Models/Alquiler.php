<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = "alquileres";

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = ["user_id", "bicicleta_id", "hora_comienzo", "hora_final", "precio_total"];

    /**
     * Relación: Obtiene la bicicleta asociada al alquiler.
     */
    public function bicicleta()
    {
        // Define una relación "belongsTo" con el modelo "Bicicleta".
        // Esto indica que un alquiler pertenece a una bicicleta específica.
        // La relación se establece utilizando la clave foránea "bicicleta_id"
        // que se encuentra en la tabla "alquileres" y hace referencia a la clave primaria "id" en la tabla "bicicletas".
        return $this->belongsTo(Bicicleta::class, "bicicleta_id");
    }

    /**
     * Relación: Obtiene el usuario que realizó el alquiler.
     */
    public function user()
    {
        // Define una relación "belongsTo" con el modelo "User".
        // Esto indica que un alquiler pertenece a un usuario específico.
        // La relación se establece utilizando la clave foránea "user_id"
        // que se encuentra en la tabla "alquileres" y hace referencia a la clave primaria "id" en la tabla "users".
        return $this->belongsTo(User::class, "user_id");
    }
}
