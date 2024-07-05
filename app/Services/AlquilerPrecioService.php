<?php

namespace App\Services;

use Carbon\Carbon;

class AlquilerPrecioService
{
    /**
     * Calcula el precio total del alquiler.
     *
     * @param string $comienzo La hora de comienzo del alquiler en formato de texto.
     * @param int $precio_por_hora El precio por hora de la bicicleta asociada al alquiler.
     * @return int El precio total del alquiler en base a la duración y el precio por hora.
     */
    public static function calcularPrecio($comienzo, $precio_por_hora)
    {
        // Obtiene la hora actual.
        $hora_final = now();

        // Crea un objeto Carbon a partir del texto de la hora de comienzo.
        $inicio = new Carbon($comienzo);

        // Calcula la diferencia en minutos entre el comienzo y la hora actual.
        $totalMinutos = $hora_final->diffInMinutes($inicio);

        // Calcula el precio por minutos utilizando el precio por hora de la bicicleta.
        $precioPorMinutos = $precio_por_hora / 60;

        // Calcula el precio total multiplicando el precio por minutos por la duración del alquiler.
        $precio_total = ceil($precioPorMinutos * $totalMinutos);

        // Retorna el precio total calculado.
        return $precio_total;
    }
}
