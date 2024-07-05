<?php

namespace App\Http\Resources;

use App\Models\Alquiler;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BicicletaResource extends JsonResource
{
    /**
     * Transforma el recurso en un arreglo.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Verificar si la bicicleta está disponible para alquiler
        $disponible = Alquiler::where('bicicleta_id', $this->id)
            ->whereNull('hora_final')
            ->exists();

        // Devuelve un arreglo con la información de la bicicleta en el formato deseado
        return [
            "id" => $this->id,
            "marca" => $this->marca,
            "modelo" => $this->modelo,
            "precio_por_hora" => $this->precio_por_hora,
            "alquilada" => $disponible,
            "foto_url" => $this->foto_url
        ];
    }
}
