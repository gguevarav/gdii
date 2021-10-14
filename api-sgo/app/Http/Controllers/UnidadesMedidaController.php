<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UnidadMedida;

class UnidadesMedidaController extends BaseController
{
	public function index(){
		// Primero obtendremos el array de los Datos
		$Datos = UnidadMedida::all();

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
        $Datos = array("NombreUnidadMedida"=>$request->input("NombreUnidadMedida"),
                       "AbreviacionUnidadMedida"=>$request->input("AbreviacionUnidadMedida"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                "NombreUnidadMedida" => 'required|string|max:255',
                "AbreviacionUnidadMedida" => 'required|string|max:5'];

            $Mensajes = [
                "NombreUnidadMedida.required" => 'Es necesario agregar un nombre de unidad de medida',
                "AbreviacionUnidadMedida.required" => 'Es necesario agregar una abreviación del nombre'];
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
                $UnidadMedida = new UnidadMedida();

                // Ingresamos los datos
                $UnidadMedida->NombreUnidadMedida = $Datos["NombreUnidadMedida"];
                $UnidadMedida->AbreviacionUnidadMedida = $Datos["AbreviacionUnidadMedida"];

                // Ejecutamos la acción de guardar
                $UnidadMedida->save();

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
        $UnidadMedida = UnidadMedida::where("idUnidadMedida", $id)->get();
        // Verificamos que el array no esté vacio
        if ($UnidadMedida != "[]" && !empty($UnidadMedida)) {
            $json = array(
                'status' => 200,
                'detalle' => $UnidadMedida
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
        $Datos = array("NombreUnidadMedida"=>$request->input("NombreUnidadMedida"),
                       "AbreviacionUnidadMedida"=>$request->input("AbreviacionUnidadMedida"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = [
                "NombreUnidadMedida" => 'required|string|max:255',
                "AbreviacionUnidadMedida" => 'required|string|max:5'];

            $Mensajes = [
                "NombreUnidadMedida.required" => 'Es necesario agregar un nombre de unidad de medida',
                "AbreviacionUnidadMedida.required" => 'Es necesario agregar una abreviación del nombre'];
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
                // Obtendremos el UnidadMedida de la base de datos
                $ObtenerUnidadMedida = UnidadMedida::where("idUnidadMedida", $id)->get();

                if(!empty($ObtenerUnidadMedida[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $UnidadMedida = UnidadMedida::where("idUnidadMedida", $id)->update($Datos);

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
        $ObtenerUnidadMedida = UnidadMedida::where("idUnidadMedida", $id)->get();
        // Si el UnidadMedida no está vacío
        if(!empty($ObtenerUnidadMedida)){
            // Eliminamos el registro
            $UnidadMedidaEliminar = UnidadMedida::where("idUnidadMedida", $id)->delete();

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