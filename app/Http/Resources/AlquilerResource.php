<?php

namespace App\Http\Resources;

use App\Services\AlquilerPrecioService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlquilerResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "hora_comienzo" => $this->hora_comienzo,
            "hora_final" => $this->hora_final,
            // Calcula el precio total del alquiler
            // Si ya tiene un precio total registrado, lo utiliza. De lo contrario, calcula el precio utilizando el servicio AlquilerPrecioService.
            "precio_total" => $this->precio_total ? $this->precio_total : AlquilerPrecioService::calcularPrecio($this->hora_comienzo, $this->bicicleta->precio_por_hora),
            "bicicleta" => [
                "bicicleta_id" => $this->bicicleta->id,
                "marca" => $this->bicicleta->marca,
                "modelo" => $this->bicicleta->modelo,
                "precio_por_hora" => $this->bicicleta->precio_por_hora,
                "foto_url" => $this->bicicleta->foto_url
            ]
        ];
    }
}
