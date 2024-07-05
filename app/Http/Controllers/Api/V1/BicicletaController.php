<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BicicletaResource;
use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controlador para la API de Bicicletas
 * @group Bicicleta
 */
class BicicletaController extends Controller
{
    /**
     * Muestra una lista de bicicletas.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Retorna una colección paginada de recursos BicicletaResource
        return BicicletaResource::collection(Bicicleta::paginate());
    }

    /**
     * Almacena una nueva bicicleta en la base de datos.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida los datos del request
        $validated = $request->validate([
            "modelo" => ["required", "string", "max:255"],
            "marca" => ["required", "string", "max:255"],
            "precio_por_hora" => ["required", "integer", "min:0"],
            "foto_url" => ["required", "string"]
        ]);

        // Crea una nueva bicicleta en la base de datos
        $bicicleta = Bicicleta::create($validated);

        // Retorna un recurso BicicletaResource para la bicicleta creada
        return BicicletaResource::make($bicicleta);
    }

    /**
     * Muestra la información de una bicicleta específica.
     * @param  \App\Models\Bicicleta  $bicicleta
     * @return \App\Http\Resources\BicicletaResource
     */
    public function show(Bicicleta $bicicleta)
    {
        // Retorna un recurso BicicletaResource para la bicicleta solicitada
        return BicicletaResource::make($bicicleta);
    }

    /**
     * Actualiza la información de una bicicleta en la base de datos.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bicicleta  $bicicleta
     * @return \App\Http\Resources\BicicletaResource
     */
    public function update(Request $request, Bicicleta $bicicleta)
    {
        // Valida los datos del request
        $validated = $request->validate([
            "modelo" => ["required", "string", "max:255"],
            "marca" => ["required", "string", "max:255"],
            "precio_por_hora" => ["required", "integer", "min:0"],
            "foto_url" => ["required", "string"]
        ]);

        // Actualiza la bicicleta con los datos validados
        $bicicleta->update($validated);

        // Retorna un recurso BicicletaResource para la bicicleta actualizada
        return BicicletaResource::make($bicicleta);
    }

    /**
     * Elimina una bicicleta de la base de datos.
     * @param  \App\Models\Bicicleta  $bicicleta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bicicleta $bicicleta)
    {
        // Elimina la bicicleta de la base de datos
        $bicicleta->delete();
        return response()->noContent();
    }
}
