<?php

namespace App\Http\Controllers;
use App\Models\ListadoActividad;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ListadoActividadCaldera;
use App\Models\ListadoMaterialActividadCaldera;

class ListadoMaterialActividadesCalderaController extends BaseController
{
    public function index(){
        // Primero obtendremos el array de los datos
        $Datos = DB::Select('SELECT LMAC.idListadoMaterialActividadCaldera,
                                          P.CodigoProducto,
                                          P.NombreProducto,
                                          LMAC.CantidadProducto ' . '
                                   FROM ListadoMaterialActividadCaldera As LMAC
                                             INNER JOIN Producto P
                                                        ON LMAC.idProducto = P.idProducto');

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
        $Datos = array("idListadoActividadCaldera"=>$request->idListadoActividad,
                       "idProducto"=>$request->idProducto,
                       "CantidadProducto"=>$request->CantidadProducto);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idListadoActividadCaldera" => 'required|integer',
                       "idProducto" => 'required|integer',
                       "CantidadProducto" => 'required|numeric'];

            $Mensajes = [
                         "idListadoActividadCaldera.required" => 'Es necesario agregar una actividad.',
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
                $ListadoActividadMaterialCaldera = new ListadoMaterialActividadCaldera();

                // Ingresamos los datos
                $ListadoActividadMaterialCaldera->idListadoActividadCaldera = $Datos["idListadoActividadCaldera"];
                $ListadoActividadMaterialCaldera->idProducto = $Datos["idProducto"];
                $ListadoActividadMaterialCaldera->CantidadProducto = $Datos["CantidadProducto"] * -1;

                // Ejecutamos la acción de guardar el usuario
                $ListadoActividadMaterialCaldera->save();

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
        $Datos = array("idListadoActividadCaldera"=>$request->idListadoActividadCaldera,
            "idProducto"=>$request->idProducto,
            "CantidadProducto"=>$request->CantidadProducto);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idListadoActividadCaldera" => 'required|integer',
                "idProducto" => 'required|integer',
                "CantidadProducto" => 'required|numeric'];

            $Mensajes = [
                "idListadoActividadCaldera.required" => 'Es necesario agregar una actividad.',
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
                $ObtenerListadoMaterialActividadCaldera = ListadoMaterialActividadCaldera::where("idListadoMaterialActividadCaldera", $id)->get();

                if(!empty($ObtenerListadoMaterialActividadCaldera[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoMaterialActividadCaldera = ListadoMaterialActividadCaldera::where("idListadoMaterialActividadCaldera", $id)->update($Datos);

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
        $ListadoMaterialActividadCaldera = DB::SELECT('SELECT LMAC.idListadoMaterialActividadCaldera,
                                                            P.CodigoProducto,
                                                            P.NombreProducto,
                                                            LMAC.CantidadProducto ' . '
                                                      FROM ListadoMaterialActividadCaldera As LMAC
                                                             INNER JOIN Producto P
                                                                        ON LMAC.idProducto = P.idProducto
                                                             WHERE idListadoActividadCaldera = ' . $id . ';');
        // Verificamos que el array no esté vacio
        if ($ListadoMaterialActividadCaldera != "[]" && !empty($ListadoMaterialActividadCaldera)) {
            $json = array(
                'status' => 200,
                'detalle' => $ListadoMaterialActividadCaldera
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
