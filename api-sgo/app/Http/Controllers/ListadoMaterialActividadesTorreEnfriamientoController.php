<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ListadoMaterialActividadTorreEnfriamiento;

class ListadoMaterialActividadesTorreEnfriamientoController extends BaseController
{
    public function index(){
        // Primero obtendremos el array de los datos
        $Datos = DB::Select('SELECT LMATE.idListadoMaterialActividadTorreEnfriamiento,
                                          P.CodigoProducto,
                                          P.NombreProducto,
                                          LMATE.CantidadProducto  ' . '
                                   FROM ListadoMaterialActividadTorreEnfriamiento As LMATE
                                            INNER JOIN Producto P
                                                       ON LMATE.idProducto = P.idProducto;');

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
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("idListadoActividadTorreEnfriamiento"=>$request->idListadoActividad,
                       "idProducto"=>$request->idProducto,
                       "CantidadProducto"=>$request->CantidadProducto);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idListadoActividadTorreEnfriamiento" => 'required|integer',
                       "idProducto" => 'required|integer',
                       "CantidadProducto" => 'required|numeric'];

            $Mensajes = [
                         "idListadoActividadTorreEnfriamiento.required" => 'Es necesario agregar una actividad.',
                         "idProducto.required" => 'Es necesario agregar un producto.',
                         "CantidadProducto.required" => 'Es necesario agregar una cantidad de producto.'];
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
                $ListadoActividadMaterialTorreEnfriamiento = new ListadoMaterialActividadTorreEnfriamiento();

                // Ingresamos los datos
                $ListadoActividadMaterialTorreEnfriamiento->idListadoActividadTorreEnfriamiento = $Datos["idListadoActividadTorreEnfriamiento"];
                $ListadoActividadMaterialTorreEnfriamiento->idProducto = $Datos["idProducto"];
                $ListadoActividadMaterialTorreEnfriamiento->CantidadProducto = $Datos["CantidadProducto"];

                // Ejecutamos la acción de guardar el usuario
                $ListadoActividadMaterialTorreEnfriamiento->save();

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

    public function update($id, Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("idListadoActividadTorreEnfriamiento"=>$request->idListadoActividadTorreEnfriamiento,
                       "idProducto"=>$request->idProducto,
                       "CantidadProducto"=>$request->CantidadProducto);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idListadoActividadTorreEnfriamiento" => 'required|integer',
                       "idProducto" => 'required|integer',
                       "CantidadProducto" => 'required|numeric'];

            $Mensajes = [
                         "idListadoActividadTorreEnfriamiento.required" => 'Es necesario agregar una actividad.',
                         "idProducto.required" => 'Es necesario agregar un producto.',
                         "CantidadProducto.required" => 'Es necesario agregar una cantidad de producto.'];
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
                // Obtendremos el ListadoActividad de la base de datos
                $ObtenerListadoMaterialActividadTorreEnfriamiento = ListadoMaterialActividadTorreEnfriamiento::Where("idListadoMaterialActividadTorreEnfriamiento", $id)->get();

                if(!empty($ObtenerListadoMaterialActividadTorreEnfriamiento[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoMaterialActividadTorreEnfriamiento = ListadoMaterialActividadTorreEnfriamiento::where("idListadoMaterialActividadTorreEnfriamiento", $id)->update($Datos);

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

    public function show($id, Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Primero obtenemos todos los registros y los almacenamos en un array
        $ListadoMaterialActividadTorreEnfriamiento = DB::SELECT('SELECT LMATE.idListadoMaterialActividadTorreEnfriamiento,
                                                                            P.CodigoProducto,
                                                                            P.NombreProducto,
                                                                            LMATE.CantidadProducto ' . '
                                                                       FROM ListadoMaterialActividadTorreEnfriamiento As LMATE
                                                                              INNER JOIN Producto P
                                                                                         ON LMATE.idProducto = P.idProducto
                                                                       WHERE idListadoActividadTorreEnfriamiento = ' . $id . ';');
        // Verificamos que el array no esté vacio
        if ($ListadoMaterialActividadTorreEnfriamiento != "[]" && !empty($ListadoMaterialActividadTorreEnfriamiento)) {
            $json = array(
                'status' => 200,
                'detalle' => $ListadoMaterialActividadTorreEnfriamiento
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
}
