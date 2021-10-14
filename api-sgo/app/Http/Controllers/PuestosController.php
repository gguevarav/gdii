<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Puesto;

class PuestosController extends BaseController
{
    public function index(){
        // Primero obtendremos el array de los Datos
        $Datos = Puesto::all();

        // Verificamos que el array no esté vacio
        if (!empty($Datos[0])) {
            $json = array(
                'status' => 200,
                'total' => count($Datos),
                'detalle' => $Datos
            );
        }else{
            $json = array(
                'status' => 200,
                'total' => 0,
                'detalle' => "No hay registros"
            );
        }
        // Mostramos la información como un json
        return response()->json($json);
    }

    public function store(Request $request){
        // Creamos un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("NombrePuesto"=>$request->input("NombrePuesto"),
                       "EstadoPuesto"=>$request->input("EstadoPuesto"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                "NombrePuesto" => 'required|string|max:255',
                "EstadoPuesto" => 'required|integer'];

            $Mensajes = [
                "NombrePuesto.required" => 'Es necesario agregar un nombre del puesto',
                "EstadoPuesto.required" => 'Es necesario agregar un estado del puesto'];
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,$Reglas,$Mensajes);

            // Revisamos la validación
            if($validacion->fails()){
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores",
                    "errores" => $validacion->errors()->all()
                );
            }else{
                // instanciamos un nuevo objeto para registro
                $Puesto = new Puesto();

                // Ingresamos los datos
                $Puesto->NombrePuesto = $Datos["NombrePuesto"];
                $Puesto->EstadoPuesto = $Datos["EstadoPuesto"];

                // Ejecutamos la acción de guardar
                $Puesto->save();

                $json = array(
                    "status" => 200,
                    "detalle" => "Registro exitoso"
                );
            }
        }else{
            $json = array(
                "status" => 404,
                "detalle" => "Registro con errores"
            );
        }
        // Devolvemos la respuesta en un Json
        return response()->json($json);
    }

    public function show($id, Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Primero obtenemos todos los registros y los almacenamos en un array
        $Puesto = Puesto::where("idPuesto", $id)->get();
        // Verificamos que el array no esté vacio
        if ($Puesto != "[]" && !empty($Puesto)) {
            $json = array(
                'status' => 200,
                'detalle' => $Puesto
            );
        }else{
            $json = array(
                'status' => 200,
                'detalle' => "Registro no encontrado."
            );
        }
        // Devolvemos la respuesta en un Json
        return response()->json($json);
    }

    public function update($id, Request $request){
        // Creamos un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("NombrePuesto"=>$request->input("NombrePuesto"),
                       "EstadoPuesto"=>$request->input("EstadoPuesto"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,[
                                                  "NombrePuesto" => 'required|string|max:255',
                                                  "EstadoPuesto" => 'required|integer']);

            // Revisamos la validación
            if($validacion->fails()){
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores"
                );
            }else{
                // Obtendremos el Puesto de la base de datos
                $ObtenerPuesto = Puesto::where("idPuesto", $id)->get();

                if(!empty($ObtenerPuesto[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $Puesto = Puesto::where("idPuesto", $id)->update($Datos);

                    $json = array(
                        "status" => 200,
                        "detalle" => "Registro editado exitosamente"
                    );
                }else{
                    $json = array(
                    "status" => "404", 
                    "detalle" => "El registro no existe."
                );
                }
            }   
        }else{
            $json = array(
                    "status" => "404", 
                    "detalle" => "Registros incompletos"
                );
        }
        // Devolvemos la respuesta en un Json
        return response()->json($json);
    }

    public function destroy($id, Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Guardemos los datos en la base de datos
        $ObtenerPuesto = Puesto::where("idPuesto", $id)->get();
        // Si el Puesto no está vacío
        if(!empty($ObtenerPuesto)){
            // Eliminamos el registro
            $PuestoEliminar = Puesto::where("idPuesto", $id)->delete();

            $json = array(
                "status" => 200,
                "detalle" => "Registro eliminado exitosamente"
            );
        }else{
            $json = array(
                    "status" => "404", 
                    "detalle" => "El registro no existe."
                );
        }
        // Devolvemos la respuesta en un Json
        return response()->json($json);
    }
}