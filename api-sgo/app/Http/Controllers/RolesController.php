<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rol;

class RolesController extends BaseController
{
    public function index(){
        // Primero obtendremos el array de los Datos
        $Datos = Rol::all();

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
        $Datos = array("NombreRol"=>$request->input("NombreRol"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,[
                                          "NombreRol" => 'required|string|max:255']);

            // Revisamos la validación
            if($validacion->fails()){
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores"
                );
            }else{
                // instanciamos un nuevo objeto para registro
                $Rol = new Rol();

                // Ingresamos los datos
                $Rol->NombreRol = $Datos["NombreRol"];

                // Ejecutamos la acción de guardar
                $Rol->save();

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
        $Rol = Rol::where("idRol", $id)->get();
        // Verificamos que el array no esté vacio
        if ($Rol != "[]" && !empty($Rol)) {
            $json = array(
                'status' => 200,
                'detalle' => $Rol
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
        $Datos = array("NombreRol"=>$request->input("NombreRol"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,[
                                          "NombreRol" => 'required|string|max:255']);

            // Revisamos la validación
            if($validacion->fails()){
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores"
                );
            }else{
                // Obtendremos el Rol de la base de datos
                $ObtenerRol = Rol::where("idRol", $id)->get();

                if(!empty($ObtenerRol[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $Rol = Rol::where("idRol", $id)->update($Datos);

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
        $ObtenerRol = Rol::where("idRol", $id)->get();
        // Si el Rol no está vacío
        if(!empty($ObtenerRol)){
            // Eliminamos el registro
            $RolEliminar = Rol::where("idRol", $id)->delete();

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