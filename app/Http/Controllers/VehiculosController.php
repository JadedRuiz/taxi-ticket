<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VehiculoModel as Vehiculo;
use Log;

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
            $vehiculos = Vehiculo::select("id_vehiculo","marca","modelo","vehiculo","tbl_vehiculos.activo","namespace","path")
            ->leftJoin("tbl_fotografias as tblF","tblF.id_fotografia","=","tbl_vehiculos.fotografia_id")
            ->get();
            foreach($vehiculos as $vehiculo) {
                $vehiculo->no_operadores = DB::table("rel_vehiculo_operador")->where("vehiculo_id",$vehiculo->id_vehiculo)->count();
            }
            return ["ok" => true,"data" => $vehiculos];
        }catch(\PdoException | \Error | \Exception $e){
            Log::error("Ha ocurrido un error: ". $e->getMessage());
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
        //Variables
        $path_img="";
        $extension="";
        $namespace="";
        $user = json_decode($this->decode_json(session('data-user')));
        #region [Validaciones]
            $request->validate([
                'vehiculo' => 'required|max:200',
                'marca' => 'required|max:200',
                'modelo' => 'required|max:150',
                'placa' => 'required|unique:tbl_vehiculos|max:10',
                'no_serie' => 'max:100',
                'aseguradora' => 'max:100',
                'poliza' => 'max:100',
                'notas' => 'max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5000'
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
                'notas.max' => 'El campo "Notas" solo acepta 500 caracteres',
                'image.image' => 'El archivo adjuntado no es una imagen',
                'image.mimes' => 'Formato de imagen no valido (jpeg, jpg o png)',
                'image.max' => 'Tamaño de imagen no valido (Menor a 5Mb)'
            ]);
            //Validamos si la imagen es una URL
            if($request["image"] == null && $request["fotografia"] != null){
                //Validamos que sea una imagen
                try {
                    $path_img = $request["fotografia"];
                    $extension = pathinfo($path_img, PATHINFO_EXTENSION);
                    $namespace= "url";
                    if(!in_array($extension,["png","jpg","jpeg"])){
                        return [ 'ok' => false, "message" => "La Imagen adjuntada no tiene la extesion permitida" ];
                    }
                }catch(\Error | \Exception $e) {
                    return [ 'ok' => false, "message" => "La URL adjuntada no pertenece a una imagen" ];
                }
            }
        #endregion
        
        #region [Preparamos la imagen para Insercion]
            if($request->hasFile("image")){
                $extension = $request->image->extension();
                $image= time().'.'.$extension;
                $path_img = '/img/Empresas/'.$user->empresa.'/vehiculos/'.$image;
                $namespace= "image";
                $request->image->move(public_path('img/Empresas/'.$user->empresa."/vehiculos"), $image);
            }
        #endregion

        #region [Insercciones a BD]
            try{
                DB::beginTransaction();
                $id_foto=0;
                if($path_img != "") {
                    $id_foto = DB::table("tbl_fotografias")->insertGetId([
                        "path" => $path_img,
                        "namespace" => $namespace,
                        "extension" => $extension,
                        "dtCreacion" => date("Y-m-d h:i:s"),
                        "activo" => 1
                    ]);
                }                
                $id = Vehiculo::insertGetId([
                    "fotografia_id" => $id_foto,
                    "vehiculo" => strtoupper($request->vehiculo),
                    "marca" => strtoupper($request->marca),
                    "modelo" => strtoupper($request->modelo),
                    "placa" => strtoupper($request->placa),
                    "no_serie" => strtoupper($request->no_serie),
                    "aseguradora" => strtoupper($request->aseguradora),
                    "poliza" => strtoupper($request->poliza),
                    "dtFinPoliza" => $request->dtFinPoliza,
                    "notas" => strtoupper($request->notas),
                    "dtCreacion" => date("Y-m-d h:i:s"),
                    "activo" => 1
                ]);
                DB::commit();
                return ["ok" => true, "data" => ["insertID" => $id]];
            }catch(\PdoException | \Error | \Exception $e) {
                DB::rollBack();
                // $this->resetearId("tbl_vehiculos","id_vehiculo");
                return [ 'ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage() ];
            }
        #endregion
    }
}
