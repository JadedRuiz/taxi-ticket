<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperadorModel as Operador;
use Illuminate\Support\Facades\DB;
use Log;

class OperadorController extends Controller
{
    // Método [obtenerOperadores]
    // Desc: Método para obtener los operadores
    public function obtenerOperadores() {
        try {
            $operdaores = Operador::select("id_operador","nombres","apellidos","correo","telefono","no_licencia","dtVigencia","path","status")
            ->leftJoin("tbl_fotografias as tblF","tblF.id_fotografia","=","fotografia_id")
            ->get();
            return ["ok" => true, "data" => $operdaores];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerOperadores]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado operadores"];
        }
    }

    public function obtenerOperadorPorId(Request $request) {
        try {
            //Bucamos el operador
           $operador = Operador::select("id_operador","nombres","apellidos","correo","telefono","no_licencia","dtVigencia","path","status","curp","edad","dtNacimiento",
           "dtIngreso","dtBaja","direccion")
           ->leftJoin("tbl_fotografias as tblF","tblF.id_fotografia","=","fotografia_id")
           ->where("id_operador", $request->id_operador)
           ->firstOrFail();
           $operador->dtVigencia = date('Y-m-d',strtotime($operador->dtVigencia));
           $operador->dtNacimiento = date('Y-m-d',strtotime($operador->dtNacimiento));
           $operador->dtIngreso = date('Y-m-d',strtotime($operador->dtIngreso));
           $operador->dtBaja = date('Y-m-d',strtotime($operador->dtBaja));
           return ["ok" => true, "data" => $operador];

        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerOperadorPorId]: ".$e->getMessage());
            return ["ok" => false, "message" => "Ha ocurrido un problema al recuperar la información del operador."];
        }
    }
    // Método [obtenerVehiculoOperadores]
    // Desc: Método para obtener los operadores
    public function obtenerVehiculoOperadores(Request $request) {
        try {
            $operadores = $this->obtenerOperadores();

            if($operadores["ok"] && count($operadores["data"]) > 0) {
                foreach($operadores["data"] as $operador) {
                    $operador->path = $operador->path != null ? asset($operador->path) : asset('/img/Image_not_available.jpg');
                    $operador->correo = $operador->correo != null ? $operador->correo : 'Sin correo';
                    $operador_vehiculo = DB::table("rel_vehiculo_operador")
                    ->where("vehiculo_id",$request->id)
                    ->where("operador_id",$operador->id_operador)
                    ->where("activo",1)
                    ->first();
                    if($operador_vehiculo) {
                        $operador->seleccionado = $operador_vehiculo->activo;
                        continue;
                    }
                    $operador->seleccionado = null;
                }
                return $operadores;
            }
            return $operadores;
            
            return ["ok" => true, "data" => $vehiculo_operadores];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerVehiculoOperadores]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado operadores"];
        }
    }

    // Método [guardarOperador]
    // Desc: Método para guardar o editar la informacion del operador
    public function guardarOperador(Request $request) {
        //Variables
        $data_foto = [
            "path" => null,
            "namespace" => "image",
            "extension" => "",
            "dtCreacion" => date('Y-m-d h:i:s'),
            "activo" => 1
        ];
        $curp_validate = $request->id_operador != null ? '' : 'unique:tbl_operadores|';
        $user = json_decode($this->decode_json(session('data-user')));
        #region [Validaciones]
            $request->validate([
                'nombres' => 'required|max:150',
                'apellidos' => 'required|max:150',
                'correo' => 'nullable|email|max:100',
                'telefono' => 'nullable|numeric|min:10',
                'no_licencia' => 'required|max:50',
                'dtVigencia' => 'required|date',
                'curp' => $curp_validate.'max:18',
                'edad' => 'nullable|numeric',
                'dtNacimiento' => 'nullable|date',
                'dtIngreso' => 'nullable|date',
                'dtBaja' => 'nullable|date',
                'status' => 'max:1',
                'direccion' => 'max:350',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5000'
            ], [
                'nombres.required' => 'El campo "Nombre(s)" es obligatorio',
                'nombres.max' => 'El campo "Nombre(s)" solo acepta 150 caracteres',
                'apellidos.required' => 'El campo "Apellido(s)" es obligatorio',
                'apellidos.max' => 'El campo "Apellido(s)" solo acepta 100 caracteres',
                'correo.max' => 'El campo "Correo" solo acepta 150 caracteres',
                'correo.email' => 'El campo "Correo" no tiene formato de correo',
                'telefono.min' => 'El campo "Telefono" espera minimo 10 digitos',
                'telefono.numeric' => 'El campo "Telefono" solo acepta numeros',
                'no_licencia.required' => 'El campo "No. Licencia" es obligatorio',
                'no_licencia.max' => 'El campo "No. Licencia" solo acepta 5- caracteres',
                'curp.unique' => 'Este curp ya se encuentra registrado',
                'curp.max' => 'El campo "Curp" solo acepta 18 caracteres',
                'edad.numeric' => 'El campo "Edad" solo acepta numeros',
                'dtNacimiento.date' => 'El campo "Fecha Nacimiento" solo acepta una fecha',
                'dtIngreso.date' => 'El campo "Fecha Ingreso" solo acepta una fecha',
                'dtBaja.date' => 'El campo "Fecha Ingreso" solo acepta una fecha',
                'status.max' => 'El campo "Status" solo acepta 1 caracter',
                'image.image' => 'El archivo adjuntado no es una imagen',
                'image.mimes' => 'Formato de imagen no valido (jpeg, jpg o png)',
                'image.max' => 'Tamaño de imagen no valido (Menor a 5Mb)'
            ]);
            //Validamos si es un update
            if($request->id_operador != null) {
                return $this->actualizarOperador($request, $user);
            }
            //Validamos si existe una imagen
            if($request->image != null && $request->hasFile("image")){
                $data_foto["extension"] = $request->image->extension();
                $image= time().'.'.$data_foto["extension"];
                $data_foto["path"] = '/img/Empresas/'.$user->empresa.'/operadores/'.$image;
                $request->image->move(public_path('img/Empresas/'.$user->empresa."/operadores"), $image);
            }
        #endregion
        
        #region [Inserciones a BD]
            try{
                DB::beginTransaction();
                $id_foto=0;
                if($data_foto["path"] != null) {
                    $id_foto = DB::table("tbl_fotografias")->insertGetId($data_foto);
                    $data_foto["path"] = asset($data_foto["path"]);
                }else {
                    $data_foto["path"] = asset('/img/Image_not_available.jpg');
                }           
                $operador = Operador::create([
                    "fotografia_id" => $id_foto,
                    "nombres" => strtoupper($request->nombres),
                    "apellidos" => strtoupper($request->apellidos),
                    "correo" => $request->correo,
                    "telefono" => $request->telefono,
                    "no_licencia" => strtoupper($request->no_licencia),
                    "dtVigencia" => $request->dtVigencia,
                    "curp" => strtoupper($request->curp),
                    "edad" => $request->edad,
                    "dtNacimiento" => $request->dtNacimiento != "" ? $request->dtNacimiento : null,
                    "dtIngreso" => $request->dtIngreso != "" ? $request->dtIngreso : null,
                    "dtBaja" => $request->dtBaja != "" ? $request->dtBaja : null,
                    "status" => $request->status,
                    "direccion" => strtoupper($request->direccion),
                    "dtCreacion" => date("Y-m-d h:i:s"),
                    "activo" => 1
                ]);
                DB::commit();
                $operador->path = $data_foto["path"];
                $operador->seleccionado = null;
                return ["ok" => true, "data" => $operador ];
            }catch(\PdoException | \Error | \Exception $e) {
                DB::rollBack();
                // $this->resetearId("tbl_vehiculos","id_vehiculo");
                return [ 'ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage() ];
            }
        #endregion
    }

    // Método [actualizarOperador]
    // Desc: Método que actualiza un operador
    public function actualizarOperador($request, $user) {
        //Variables
        $foto_ant = DB::table("tbl_operadores")->select("path","fotografia_id")
        ->leftJoin("tbl_fotografias as tblF","tblF.id_fotografia","=","fotografia_id")
        ->where("id_operador",$request->id_operador)
        ->first();
        $data_foto = [
            "path" => null,
            "namespace" => "image",
            "extension" => "",
            "dtCreacion" => date('Y-m-d h:i:s'),
            "activo" => 1
        ];
        #region [Validaciones]
            //Validamos si existe una imagen
            if($request->image != null && $request->hasFile("image")){
                //Validamos si el path anterior era una imagen
                if($foto_ant->path != null && file_exists(public_path($foto_ant->path))) {
                    //Eliminamos la imagen ant
                    unlink(public_path($foto_ant->path));
                }
                $data_foto["extension"] = $request->image->extension();
                $image= time().'.'.$data_foto["extension"];
                $data_foto["path"] = '/img/Empresas/'.$user->empresa.'/operadores/'.$image;
                $request->image->move(public_path('img/Empresas/'.$user->empresa."/operadores"), $image);
            }
        #endregion
        #region [Inserciones a BD]
            try{
                $id_foto=$foto_ant->fotografia_id;
                DB::beginTransaction();
                if($foto_ant->fotografia_id == 0 && $request->hasFile("image")) {
                    $id_foto = DB::table("tbl_fotografias")->insertGetId($data_foto);
                }
                if($foto_ant->fotografia_id != 0 && $request->hasFile("image")) {
                    DB::table("tbl_fotografias")
                    ->where("id_fotografia",$foto_ant->fotografia_id)
                    ->update($data_foto);
                }
                $operador = Operador::find($request->id_operador);
                $operador->fotografia_id = $id_foto;
                $operador->nombres = strtoupper($request->nombres);
                $operador->apellidos = strtoupper($request->apellidos);
                $operador->correo = $request->correo;
                $operador->telefono = $request->telefono;
                $operador->no_licencia = strtoupper($request->no_licencia);
                $operador->dtVigencia = $request->dtVigencia;
                $operador->curp = strtoupper($request->curp);
                $operador->edad = $request->edad;
                $operador->dtNacimiento = $request->dtNacimiento != "" ? $request->dtNacimiento : null;
                $operador->dtIngreso = $request->dtIngreso != "" ? $request->dtIngreso : null;
                $operador->dtBaja = $request->dtBaja != "" ? $request->dtBaja : null;
                $operador->status = $request->status;
                $operador->direccion = strtoupper($request->direccion);
                $operador->dtCreacion = date("Y-m-d h:i:s");
                $operador->activo = 1;
                $operador->save();
                DB::commit();
                return [ 'ok' => true, "message" => "Operador Actualizado" ];
                
            } catch(\PdoException | \Error | \Exception $e) {
                DB::rollBack();
                Log::error("ERROR En método [actualizarOperador]: ".$e->getMessage());
                return [ 'ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage() ];
            }
        #endregion
    }

    // Método [asignarOperadorVehiculo]
    // Desc: Método para asignar operador a vehiculo
    public function asignarOperadorVehiculo(Request $request) {
        try {
            #region [Validaciones]
                $validar_operador = DB::table("rel_vehiculo_operador")
                ->select("tblV.vehiculo")
                ->leftJoin("tbl_vehiculos as tblV","tblV.id_vehiculo","=","vehiculo_id")
                ->where("operador_id",$request->id_operador)
                ->where("vehiculo_id","<>",$request->id_vehiculo)
                ->where("rel_vehiculo_operador.activo",1)
                ->first();
                if($validar_operador) {
                    return ["ok" => false, "message" => "Este operador ya ha sido asigando al vehiculo: ".$validar_operador->vehiculo];
                }
                $validar_cantidad_operadores = DB::table("rel_vehiculo_operador")
                ->where("vehiculo_id",$request->id_vehiculo)
                ->where("activo",1)
                ->count();
                if($validar_cantidad_operadores >= 1) {
                    return ["ok" => false, "message" => "Este vehiculo solo puede tener un operador por horario laboral"];
                }
            #endregion
            //Valida Status
            $validar = DB::table("rel_vehiculo_operador")
            ->where("operador_id",$request->id_operador)
            ->where("vehiculo_id",$request->id_vehiculo)
            ->first();
            if($validar) {
                $validar->activo = $validar->activo == 1 ? 0 : 1;
                DB::table('rel_vehiculo_operador')
                ->where('id_vehiculo_operador',$validar->id_vehiculo_operador)
                ->update([
                    "activo" => $validar->activo
                ]);
                return ["ok" => true, "data" => 'Actualizado'];
            }
            DB::table('rel_vehiculo_operador')->insert([
                "operador_id" => $request->id_operador,
                "vehiculo_id" => $request->id_vehiculo,
                "dtCreacion" => date('Y-m-d h:i:s'),
                "activo" => 1
            ]);
            return ["ok" => true, "data" => 'Selecciona guardada'];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [aseignarOperadorVehiculo]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se pudo asinar el operador al vehiculo"];
        }
    }
}
