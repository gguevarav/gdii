<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\MinimosMaximos;
use Illuminate\Support\Facades\DB;

class InventarioController extends BaseController
{
    public function index(){
        // Creamos el join para obtener el array de los datos
        $Datos = DB::table('Inventario')
            ->join('Producto', 'Inventario.idProducto', '=', 'Producto.idProducto')
            ->join('MinimosMaximos', 'Inventario.idProducto', '=', 'MinimosMaximos.idProducto')
            ->select('Producto.idProducto', 'Producto.CodigoProducto', 'Producto.NombreProducto',
                DB::raw('SUM(CantidadExistencia) as TotalExistencia'),
                DB::raw('SUM(ProductoFlotante) as TotalFlotante'),
                'MinimosMaximos.CantidadMinima', 'MinimosMaximos.CantidadMaxima')
            ->groupBy('Producto.idProducto')
            ->get();

        //SELECT Inventario.*, SUM(CantidadExistencia) as TotalExistencia FROM Inventario GROUP by idProducto;

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
        $Datos = array("idProducto"=>$request->input("idProducto"),
                       "CantidadExistencia"=>$request->input("CantidadExistencia"),
                       "CantidadMinima"=>$request->input("CantidadMinima"),
                       "CantidadMaxima"=>$request->input("CantidadMaxima"),
                       "RegistradoPor"=>$request->input("RegistradoPor"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                "idProducto" => 'required|integer',
                "CantidadExistencia" => 'required|numeric',
                "CantidadMinima" => 'required|numeric',
                "CantidadMaxima" => 'required|numeric',
                "RegistradoPor" => 'required|integer',
                ""];

            $Mensajes = [
                "idProducto.required" => 'Es necesario agregar un código de producto',
                "CantidadExistencia.required" => 'Es necesario agregar una cantidad en existencia',
                "CantidadMinima.required" => 'Es necesario agregar una cantidad minima',
                "CantidadMaxima.required" => 'Es necesario agregar una cantidad maxima',
                "RegistradoPor.required" => 'Es necesario agregar un usuario que haya registrado'];
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
            }else if($Datos["CantidadMaxima"] <= $Datos["CantidadMinima"]) {
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores",
                    "errores" => [
                        0 => "La cantidad máxima de producto no puede ser menor ni igual que la cantidad mínima"
                    ]
                );
            }else{
                // Guardamos primero los minimos y maximos
                $MinimosMaximos = new MinimosMaximos();

                $MinimosMaximos->idProducto = $Datos["idProducto"];
                $MinimosMaximos->CantidadMinima = $Datos["CantidadMinima"];
                $MinimosMaximos->CantidadMaxima = $Datos["CantidadMaxima"];

                $MinimosMaximos->save();

                // instanciamos un nuevo objeto para registro
                $Inventario = new Inventario();

                // Ingresamos los datos
                $Inventario->idProducto = $Datos["idProducto"];
                $Inventario->CantidadExistencia = $Datos["CantidadExistencia"];
                $Inventario->RegistradoPor = $Datos["RegistradoPor"];

                // Ejecutamos la acción de guardar el usuario
                $Inventario->save();

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

    public function descontarProductosInventario(Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("idProducto"=>$request->input("idProducto"),
                       "CantidadExistencia"=>$request->input("CantidadExistencia"),
                       "idListadoActividadCaldera"=>$request->input("idListadoActividadCaldera"),
                       "idListadoActividadPretratamiento"=>$request->input("idListadoActividadPretratamiento"),
                       "idListadoActividadTorreEnfriamiento"=>$request->input("idListadoActividadTorreEnfriamiento"),
                       "RegistradoPor"=>$request->input("RegistradoPor"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idProducto" => 'required|integer',
                       "CantidadExistencia" => 'required|numeric',
                       "idListadoActividadCaldera" => 'required|numeric',
                       "idListadoActividadPretratamiento" => 'required|numeric',
                       "idListadoActividadTorreEnfriamiento" => 'required|numeric',
                       "RegistradoPor" => 'required|integer'];

            $Mensajes = ["idProducto.required" => 'Es necesario agregar un código de producto.',
                         "CantidadExistencia.required" => 'Es necesario agregar una cantidad en existencia.',
                         "idListadoActividadCaldera.required" => 'Es necesario agregar una actividad de caldera.',
                         "idListadoActividadPretratamiento.required" => 'Es necesario agregar una actividad de pretratamiento.',
                         "idListadoActividadTorreEnfriamiento.required" => 'Es necesario agregar una actividad de torre de enfriamiento.',
                         "RegistradoPor.required" => 'Es necesario agregar un usuario que haya registrado.'];
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
                $Inventario = new Inventario();

                // Ingresamos los datos
                $Inventario->idProducto = $Datos["idProducto"];
                $Inventario->idListadoActividadCaldera = $Datos["idListadoActividadCaldera"];
                $Inventario->idListadoActividadPretratamiento = $Datos["idListadoActividadPretratamiento"];
                $Inventario->idListadoActividadTorreEnfriamiento = $Datos["idListadoActividadTorreEnfriamiento"];
                $Inventario->CantidadExistencia = $Datos["CantidadExistencia"] * -1;
                $Inventario->ProductoFlotante = $Datos["CantidadExistencia"] * -1;
                $Inventario->RegistradoPor = $Datos["RegistradoPor"];

                // Ejecutamos la acción de guardar el usuario
                $Inventario->save();

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
        $Inventario = Inventario::where("idInventario", $id)->get();
        // Verificamos que el array no esté vacio
        if ($Inventario != "[]" && !empty($Inventario)) {
            $json = array(
                'status' => 200,
                'detalle' => $Inventario
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

    public function update2(){
        echo "HolaMundo";
    }

    public function update($id, Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("idProducto"=>$request->idProducto,
                       "CantidadMinima"=>$request->CantidadMinima,
                       "CantidadMaxima"=>$request->CantidadMaxima);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                       "idProducto" => 'required|integer',
                       "CantidadMinima" => 'required|numeric',
                       "CantidadMaxima" => 'required|numeric'];

            $Mensajes = [
                         "idProducto.required" => 'Es necesario agregar un código de producto',
                         "CantidadMinima.required" => 'Es necesario agregar una cantidad minima',
                         "CantidadMaxima.required" => 'Es necesario agregar una cantidad maxima'];
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,$Reglas,$Mensajes);

            //return echo "Hola";

            // Revisamos la validación
            if($validacion->fails()){
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores",
                    "errores" => $validacion->errors()->all()
                );
            }else if($Datos["CantidadMaxima"] <= $Datos["CantidadMinima"]) {
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores",
                    "errores" => [
                        0 => "La cantidad máxima de producto no puede ser menor ni igual que la cantidad mínima"
                    ]
                );
            }else{
                // Ejecutamos la acción de guardar el usuario
                $ObtenerObjetoEditar = MinimosMaximos::where("idProducto", $id)->get();

                if(!empty($ObtenerObjetoEditar[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $Inventario = MinimosMaximos::where("idProducto", $id)->update($Datos);

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
        $ObtenerInventario = Inventario::where("idInventario", $id)->get();
        // Si el producto no está vacío
        if(!empty($ObtenerInventario)){
            // Eliminamos el registro
            $InventarioEliminar = Inventario::where("idInventario", $id)->delete();

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

    public function productoNoInventariado(){
        // Devolveremos solo los productos que aún no se han inventariado
        $Datos = Producto::leftJoin('Inventario', function($join) {
            $join->on('Producto.idProducto', '=', 'Inventario.idProducto');
        })
            ->select('Producto.*')
            ->whereNull('Inventario.idProducto')
            ->get();


        //select * from Producto left join Inventario on Producto.idProducto = Inventario.idProducto
        //where Inventario.idProducto is null

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

    public function productoInventariado(){
        // Devolveremos solo los productos que ya se han inventariado
        $Datos = DB::table('Producto')
            ->join('Inventario', 'Producto.idProducto', '=', 'Inventario.idProducto')
            ->join('UnidadMedida', 'Producto.idUnidadMedida', '=', 'UnidadMedida.idUnidadMedida')
            ->select('Producto.idProducto', 'Producto.CodigoProducto', 'Producto.NombreProducto',
                             'UnidadMedida.NombreUnidadMedida', 'UnidadMedida.AbreviacionUnidadMedida',
                             'Inventario.CantidadExistencia',
                              DB::raw('SUM(Inventario.CantidadExistencia) as TotalExistencia'))
            ->groupBy('Producto.idProducto')
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

    public function agregarCantidadInventario(Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("idProducto"=>$request->input("idProducto"),
            "cantidadAgregar"=>$request->input("cantidadAgregar"),
            "RegistradoPor"=>$request->input("RegistradoPor"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                "idProducto" => 'required|integer',
                "cantidadAgregar" => 'required|numeric',
                "RegistradoPor" => 'required|numeric'];

            $Mensajes = [
                "idProducto.required" => 'Es necesario agregar un código de producto',
                "cantidadAgregar.required" => 'Es necesario agregar una cantidad en existencia',
                "RegistradoPor.required" => 'Es necesario agregar un usuario'];
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,$Reglas,$Mensajes);

            //return echo "Hola";

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
                $Inventario = new Inventario();

                // Ingresamos los datos
                $Inventario->idProducto = $Datos["idProducto"];
                $Inventario->CantidadExistencia = $Datos["cantidadAgregar"];
                $Inventario->RegistradoPor = $Datos["RegistradoPor"];

                // Ejecutamos la acción de guardar el usuario
                $Inventario->save();

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
}
