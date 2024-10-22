<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VehiculoModel as Vehiculo;

class VehiculosController extends Controller
{
    public function index() {
        if(session()->has('data-user')) {
            $user = json_decode($this->decode_json(session('data-user')));
            $entries = ["public/js/vehiculo.js", "public/sass/vehiculo.scss"];
            $vehiculos = $this->obtenerVehiculos();

            return view('admin/Vehiculos', compact('user','entries','vehiculos'));
        }
        return view('admin/template/Login');
    }

    /// Método [obtenerVehiculos]
    /// Desc: Método para obtener todos los vehiculos
    public function obtenerVehiculos() {
        try {
            $vehiculos = Vehiculo::select("id_vehiculo","marca","modelo","vehiculo","activo")
            ->get();
            foreach($vehiculos as $vehiculo) {
                $vehiculo->no_operadores = DB::table("rel_vehiculo_operador")->where("vehiculo_id",$vehiculo->id_vehiculo)->count();
            }
            return ["ok" => true,"data" => $vehiculos];
        }catch(\PdoException | \Error | \Exception $e){
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
        
    }

    /// Método [obtenerVehiculosPorId]
    /// Desc: Método para obtener todos los vehiculos
    public function obtenerVehiculosPorId($id) {
        try {
            $vehiculos = Vehiculo::where("id_vehiculo",$id)
            ->get();
            return ["ok" => true,"data" => $vehiculos];
        }catch(\PdoException | \Error | \Exception $e){
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
        
    }

    /// Método [guardarVehiculo]
    /// Desc: Método para agregar un vehiculo con sus operadores 
    public function guardarVehiculo(Request $request) {
        #region [Validaciones]
            $validated = $request->validate([
                'vehiculo' => 'required|max:200',
                'marca' => 'required|max:200',
                'modelo' => 'required|max:150',
                'placa' => 'required|unique:tbl_vehiculos|max:10',
                'no_serie' => 'max:100',
                'aseguradora' => 'max:100',
                'poliza' => 'max:100',
                'notas' => 'max:500'
            ], [
                'vehiculo.required' => 'El campo "Vehiculo" es obligatorio',
                'vehiculo.max' => 'El campo "Vehiculo" solo acepta 200 caracteres',
                'marca.required' => 'El campo "Marca" es obligatorio',
                'marca.max' => 'El campo "Marca" solo acepta 200 caracteres',
                'modelo.required' => 'El campo "Modelo" es obligatorio',
                'modelo.max' => 'El campo "Modelo" solo acepta 150 caracteres',
                'placa.required' => 'El campo "Placa" es obligatorio',
                'placa.max' => 'El campo "Placa" solo acepta 10 caracteres',
                'placa.unique' => 'La placa ya se encuentra registrada',
                'no_serie.max' => 'El campo "Placa" solo acepta 10 caracteres',
                'aseguradora.max' => 'El campo "No. serie" solo acepta 10 caracteres',
                'poliza.max' => 'El campo "Poliza" solo acepta 10 caracteres',
                'notas.max' => 'El campo "Notas" solo acepta 500 caracteres'
            ]);
        #endregion
        
        #region [Insercciones a BD]
            try{
                DB::beginTransaction();
                $id = Vehiculo::insertGetId($request->all());
                DB::commit();
                return ["ok" => true, "data" => ["insertID" => $id]];
            }catch(\PdoException | \Error | \Exception $e) {
                DB::rollBack();
                $this->resetearId("tbl_vehiculos","id_vehiculo");
                return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
            }
        #endregion
    }
}
