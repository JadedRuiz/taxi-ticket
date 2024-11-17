<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VehiculoModel as Vehiculo;
use App\Http\Controllers\OperadorController as Operador;
use Log;

class VehiculosController extends Controller
{
    public function index() {
        if(session('user')) {
            $user = json_decode($this->decode_json(session('user')[0]));
            $entries = ["public/js/vehiculo.js", "public/js/operador.js", "public/sass/vehiculo.scss","public/sass/operador.scss"];
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
                $vehiculo->no_operadores = DB::table("rel_vehiculo_operador")
                ->where("vehiculo_id",$vehiculo->id_vehiculo)
                ->where("activo",1)
                ->count();
            }
            return ["ok" => true,"data" => $vehiculos];
        }catch(\PdoException | \Error | \Exception $e){
            Log::error("Ha ocurrido un error: ". $e->getMessage());
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
        
    }

    /// Método [obtenerVehiculosPorId]
    /// Desc: Método para obtener todos los vehiculos
    public function obtenerVehiculosPorId(Request $request) {
        try {
            $vehiculo = Vehiculo::select("id_vehiculo","fotografia_id","path as fotografia","vehiculo","marca","modelo","placa","no_serie",
            "aseguradora","poliza","dtFinPoliza","notas")
            ->leftJoin("tbl_fotografias as tblF","tblF.id_fotografia","=","fotografia_id")
            ->where("id_vehiculo",$request["id"])
            ->firstOrFail();
            //Formateamos fecha
            $vehiculo->dtFinPoliza = date('Y-m-d',strtotime($vehiculo->dtFinPoliza));
            return ["ok" => true,"data" => $vehiculo];
        }catch(\PdoException | \Error | \Exception $e){
            Log::error("Ha ocurrido un error: ". $e->getMessage());
            return [ 'ok' => false, "message" => "No se ha podido obtener la información de este vehiculo" ];
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
        $validate = $request->id_vehiculo != 0 ? "" : "unique:tbl_vehiculos|";
        #region [Validaciones]
            $request->validate([
                'vehiculo' => 'required|max:200',
                'marca' => 'required|max:200',
                'modelo' => 'required|max:150',
                'placa' => 'required|'.$validate.'max:10',
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
            //Validamos si es un update
            if($request->id_vehiculo != 0) {
                return $this->actualizarVehiculo($request, $user);
            }
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
                return ["ok" => true, "data" => "Datos guardados existosamente"];
            }catch(\PdoException | \Error | \Exception $e) {
                DB::rollBack();
                // $this->resetearId("tbl_vehiculos","id_vehiculo");
                return [ 'ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage() ];
            }
        #endregion
    }

    // Método [actualizarVehiculo]
    // Desc: Método que actualiza la información de vehiculo
    public function actualizarVehiculo($request, $user) {
        // Variables;
        $data = [
            "tipo_trato" => 2, //0 Insertar, 1 Actualización, 2 Nada (Default)
            "data_foto" => [
                "path" => "",
                "namespace" => "",
                "extension" => "",
                "dtCreacion" => date('Y-m-d h:i:s'),
                "activo" => 1,
            ]            
        ];
        $foto_ant = DB::table("tbl_fotografias")->where("id_fotografia",$request->fotografia_id)->first();
        $vehiculo = Vehiculo::find($request->id_vehiculo); 
        $id_foto=$request->fotografia_id;
        #region [Validaciones]
            //Validamos que el vehiculo exista en BD
            if(!$vehiculo) {
                return ["ok" => false, "message" => "Este vehiculo no existe"];
            }
            //Validamos si hay un cambio en la placa && si la nueva no existe registrada.
            if($vehiculo->placa != $request->placa) {
                $validar_placa = Vehiculo::where("placa",$request->placa)->first();
                if($validar_placa) {
                    return ["ok" => false, "message" => "La placa que intenta actualizar ya pertenece a otro vehiculo"];
                }
            }
            //Se validamos que tenga una foto anterior y que la nueva sea una URL
            if($foto_ant && $request->image == null && $request->fotografia != null) {
                //Se valida que no sea la misma que en bd
                if($foto_ant->path != $request->fotografia) {                    
                    $data["data_foto"]["path"] = $request->fotografia;             
                    $data["data_foto"]["extension"] = pathinfo($data["data_foto"]["path"], PATHINFO_EXTENSION);
                    //Validamos que la URL sea una imagen
                    if(!in_array($data["data_foto"]["extension"],["png","jpg","jpeg"])){
                        return [ 'ok' => false, "message" => "La Imagen adjuntada no tiene la extesion permitida" ];
                    }
                    //Validamos si el path anterior era una imagen
                    if(file_exists(public_path($foto_ant->path))) {
                        //Eliminamos la imagen ant
                        unlink(public_path($foto_ant->path));
                    }
                    $data["tipo_trato"] = 1;
                    $data["data_foto"]["namespace"] = "url";
                }
            }
            //Validamos que tenga una foto ant y que la nueva se una imagen adjunta
            if($foto_ant && $request->hasFile("image")){
                //Validamos si el path anterior era una imagen
                if(file_exists(public_path($foto_ant->path))) {
                    //Eliminamos la imagen ant
                    unlink(public_path($foto_ant->path));
                }
                $data["tipo_trato"] = 1;
                $data["data_foto"]["extension"] = $request->image->extension();
                $image= time().'.'.$data["data_foto"]["extension"];
                $data["data_foto"]["path"] = '/img/Empresas/'.$user->empresa.'/vehiculos/'.$image;
                $data["data_foto"]["namespace"] = "image";
                $request->image->move(public_path('img/Empresas/'.$user->empresa."/vehiculos"), $image);
            }
            //Validamos que no tenga foto ant y que la nueva sea una URL
            if(!$foto_ant && $request->image == null && $request->fotografia) {
                $data["data_foto"]["path"] = $request->fotografia;             
                $data["data_foto"]["extension"] = pathinfo($data["data_foto"]["path"], PATHINFO_EXTENSION);
                //Validamos que la URL sea una imagen
                if(!in_array($data["data_foto"]["extension"],["png","jpg","jpeg"])){
                    return [ 'ok' => false, "message" => "La Imagen adjuntada no tiene la extesion permitida" ];
                }
                $data["tipo_trato"] = 0;
                $data["data_foto"]["namespace"] = "url";
            }
            //Validamos que no tenga una fot ant y que la nueva sea una imagen
            if(!$foto_ant && $request->hasFile("image")){
                $data["tipo_trato"] = 0;
                $data["data_foto"]["extension"] = $request->image->extension();
                $image= time().'.'.$data["data_foto"]["extension"];
                $data["data_foto"]["path"] = '/img/Empresas/'.$user->empresa.'/vehiculos/'.$image;
                $data["data_foto"]["namespace"] = "image";
                $request->image->move(public_path('img/Empresas/'.$user->empresa."/vehiculos"), $image);
            }
            //Validamos que tenga una foto anterior y que no haya eliminado URL/Imagen
            if($foto_ant && $request->fotografia == null) {
                $id_foto = 0;
                //Validamos si la foto ant era una imagen
                if(file_exists(public_path($foto_ant->path))) {
                    //Eliminamos la imagen ant
                    unlink(public_path($foto_ant->path));
                }
                DB::table("tbl_fotografias")->where("id_fotografia",$request->fotografia_id)->delete();
            }

        #endregion
        
        #region [Inserciones]
            //INSERT FOTO NUEVA
            if($data["tipo_trato"] == 0) {
                $id_foto = DB::table("tbl_fotografias")->insertGetId($data["data_foto"]);
            }
            //UPDATE FOTO
            if($data["tipo_trato"] == 1) {
                DB::table("tbl_fotografias")
                ->where("id_fotografia",$request->fotografia_id)
                ->update($data["data_foto"]);
            }
            //Actualizamos la data del vehiculo
            $vehiculo = Vehiculo::find($request->id_vehiculo);
            $vehiculo->fotografia_id =  $vehiculo->fotografia_id != $id_foto ? $id_foto : $vehiculo->fotografia_id;
            $vehiculo->vehiculo =  $vehiculo->vehiculo != $request->vehiculo ? strtoupper($request->vehiculo) : $vehiculo->vehiculo;
            $vehiculo->marca =  $vehiculo->marca != $request->marca ? strtoupper($request->marca) : $vehiculo->marca;
            $vehiculo->modelo =  $vehiculo->modelo != $request->modelo ? strtoupper($request->modelo) : $vehiculo->modelo; 
            $vehiculo->placa =  $vehiculo->placa != $request->placa ? strtoupper($request->placa) : $vehiculo->placa;
            $vehiculo->no_serie =  $vehiculo->no_serie != $request->no_serie ? $request->no_serie : $vehiculo->no_serie;
            $vehiculo->aseguradora =  $vehiculo->aseguradora != $request->aseguradora ? strtoupper($request->aseguradora) : $vehiculo->aseguradora;
            $vehiculo->poliza =  $vehiculo->poliza != $request->poliza ? strtoupper($request->poliza) : $vehiculo->poliza;
            $vehiculo->dtFinPoliza =  $vehiculo->dtFinPoliza != $request->dtFinPoliza ? $request->dtFinPoliza : $vehiculo->dtFinPoliza;
            $vehiculo->notas =  $vehiculo->notas != $request->notas ? strtoupper($request->notas) : $vehiculo->notas;
            $vehiculo->save();
        #endregion
        
        return ["ok" => true, "data" => "Datos guardados existosamente"];
    }
}
