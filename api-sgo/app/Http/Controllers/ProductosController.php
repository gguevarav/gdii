<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductosController extends BaseController
{
    public function index(){
        // Creamos el join para obtener el array de los datos
        $Datos = DB::table('Producto')
                        ->join('UnidadMedida', 'Producto.idUnidadMedida', '=', 'UnidadMedida.idUnidadMedida')
                        ->join('Estado', 'Producto.EstadoProducto', '=', 'Estado.idEstado')
                        ->select('Producto.*', 'UnidadMedida.NombreUnidadMedida', 'Estado.NombreEstado')
                        ->get();

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
        $Datos = array("CodigoProducto"=>$request->input("CodigoProducto"),
                       "NombreProducto"=>$request->input("NombreProducto"),
                       "idUnidadMedida"=>$request->input("idUnidadMedida"),
                       "EstadoProducto"=>$request->input("EstadoProducto"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                "CodigoProducto" => 'required|string|max:255|unique:Producto',
                "NombreProducto" => 'required|string|max:255',
                "idUnidadMedida" => 'required|integer',
                "EstadoProducto" => 'required|integer'];

            $Mensajes = [
                "CodigoProducto.required" => 'Es necesario agregar un código de producto',
                "CodigoProducto.unique" => 'El código ya existe',
                "NombreProducto.required" => 'Es necesario agregar un nombre al producto',
                "idUnidadMedida.required" => 'Es necesario agregar una unidad de medida',
                "EstadoProducto.required" => 'Es necesario agregar un estado de producto'];
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
                $Producto = new Producto();

                // Ingresamos los datos
                $Producto->CodigoProducto = $Datos["CodigoProducto"];
                $Producto->NombreProducto = $Datos["NombreProducto"];
                $Producto->idUnidadMedida = $Datos["idUnidadMedida"];
                $Producto->EstadoProducto = $Datos["EstadoProducto"];

                // Ejecutamos la acción de guardar el usuario
                $Producto->save();

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
        $Producto = Producto::where("idProducto", $id)->get();
        // Verificamos que el array no esté vacio
        if ($Producto != "[]" && !empty($Producto)) {
            $json = array(
                'status' => 200,
                'detalle' => $Producto
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
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("CodigoProducto"=>$request->input("CodigoProducto"),
                       "NombreProducto"=>$request->input("NombreProducto"),
                       "idUnidadMedida"=>$request->input("idUnidadMedida"),
                       "EstadoProducto"=>$request->input("EstadoProducto"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                //"CodigoProducto" => 'required|string|max:255|unique:Producto',
                "NombreProducto" => 'required|string|max:255',
                "idUnidadMedida" => 'required|integer',
                "EstadoProducto" => 'required|integer'];

            $Mensajes = [
                //"CodigoProducto.required" => 'Es necesario agregar un código de producto',
                "CodigoProducto.unique" => 'El código ya existe',
                "NombreProducto.required" => 'Es necesario agregar un nombre al producto',
                "idUnidadMedida.required" => 'Es necesario agregar una unidad de medida',
                "EstadoProducto.required" => 'Es necesario agregar un estado de producto'];
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
                // Obtendremos el producto de la base de datos
                $ObtenerProducto = Producto::where("idProducto", $id)->get();

                if(!empty($ObtenerProducto[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $Producto = Producto::where("idProducto", $id)->update($Datos);

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
        $ObtenerProducto = Producto::where("idProducto", $id)->get();
        // Si el producto no está vacío
        if(!empty($ObtenerProducto)){
            // Eliminamos el registro
            $ProductoEliminar = Producto::where("idProducto", $id)->delete();

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
