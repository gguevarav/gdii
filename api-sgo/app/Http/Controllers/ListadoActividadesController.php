<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ListadoActividad;

class ListadoActividadesController extends BaseController
{
	public function index(){
		// Primero obtendremos el array de los datos
		$Datos = ListadoActividad::all();

		/*
		 * SELECT L.idArea,
                   L.idNombreActividad,
                   L.CreadoPor,
                   L.RealizadoPor,
                   L.FechaCreacionActividad,
                   L.FechaConclusionActividad,
                   L.EstadoActividad,
                   A.NombreArea,
                   N.idNombreActividad,
                   U.NombreUsuario,
                   U.ApellidoUsuario,
                   u2.NombreUsuario,
                   u2.ApellidoUsuario FROM ListadoActividad As L
                INNER JOIN Area A
                ON L.idArea = A.idArea
                INNER JOIN NombreActividad N
                ON L.idNombreActividad = N.idNombreActividad
                INNER JOIN users U
                ON L.CreadoPor = U.idUsuario
                INNER JOIN users u2
                ON L.RealizadoPor = u2.idUsuario;
		 * */

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
        $Datos = array("idArea"=>$request->input("idArea"),
                       "idNombreActividad"=>$request->input("idNombreActividad"),
                       "FechaCreacionActividad"=>$request->input("FechaCreacionActividad"),
                       "FechaConclusionActividad"=>$request->input("FechaConclusionActividad"),
                       "EstadoActividad"=>$request->input("EstadoActividad"),
                       "idUsuario"=>$request->input("idUsuario"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,[
                                          "idArea" => 'required|integer',
                                          "idNombreActividad" => 'required|integer',
                                          "FechaCreacionActividad" => 'required|date',
                                          "FechaConclusionActividad" => 'required|date',
                                          "EstadoActividad" => 'required|boolean',
                                          "idUsuario" => 'required|integer']);

            // Revisamos la validación
            if($validacion->fails()){
                // Devolvemos el mensaje que falló la validación de Datos
                $json = array(
                    "status" => 404,
                    "detalle" => "Los registros tienen errores"
                );
            }else{
                // instanciamos un nuevo objeto para registro
                $ListadoActividad = new ListadoActividad();

                // Ingresamos los datos
                $ListadoActividad->idArea = $Datos["idArea"];
                $ListadoActividad->idNombreActividad = $Datos["idNombreActividad"];
                $ListadoActividad->FechaCreacionActividad = $Datos["FechaCreacionActividad"];
                $ListadoActividad->FechaConclusionActividad = $Datos["FechaConclusionActividad"];
                $ListadoActividad->EstadoActividad = $Datos["EstadoActividad"];
                $ListadoActividad->idUsuario = $Datos["idUsuario"];

                // Ejecutamos la acción de guardar el usuario
                $ListadoActividad->save();

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
        $ListadoActividad = ListadoActividad::where("idListadoActividad", $id)->get();
        // Verificamos que el array no esté vacio
        if ($ListadoActividad != "[]" && !empty($ListadoActividad)) {
            $json = array(
                'status' => 200,
                'detalle' => $ListadoActividad
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
        $Datos = array("idArea"=>$request->input("idArea"),
                       "idNombreActividad"=>$request->input("idNombreActividad"),
                       "FechaCreacionActividad"=>$request->input("FechaCreacionActividad"),
                       "FechaConclusionActividad"=>$request->input("FechaConclusionActividad"),
                       "EstadoActividad"=>$request->input("EstadoActividad"),
                       "idUsuario"=>$request->input("idUsuario"));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Validamos los Datos antes de insertarlos en la base de Datos
            $validacion = Validator::make($Datos,[
                                            "idArea" => 'required|integer',
                                            "idNombreActividad" => 'required|integer',
                                            "FechaCreacionActividad" => 'required|date',
                                            "FechaConclusionActividad" => 'required|date',
                                            "EstadoActividad" => 'required|boolean',
                                            "idUsuario" => 'required|integer']);

            // Si falla la validación
            if ($validacion->fails()){
                $json = array(
                    "status" => "404",
                    "detalle" => "Registro con errores"
                );
            }else{
                // Obtendremos el ListadoActividad de la base de datos
                $ObtenerListadoActividad = ListadoActividad::where("idListadoActividad", $id)->get();

                if(!empty($ObtenerListadoActividad[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoActividad = ListadoActividad::where("idListadoActividad", $id)->update($Datos);

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
        $ObtenerListadoActividad = ListadoActividad::where("idListadoActividad", $id)->get();
        // Si el ListadoActividad no está vacío
        if(!empty($ObtenerListadoActividad)){
            // Eliminamos el registro
            $ListadoActividadEliminar = ListadoActividad::where("idListadoActividad", $id)->delete();

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
