<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperadorModel as Operador;
use Log;

class OperadorController extends Controller
{
    // Método [obtenerOperadores]
    // Desc: Método para obtener los operadores
    public function obtenerOperadores() {
        try {
            $operdaores = Operador::get();
            return ["ok" => true, "data" => $operdaores];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerOperadores]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado operadores"];
        }
    }
}
