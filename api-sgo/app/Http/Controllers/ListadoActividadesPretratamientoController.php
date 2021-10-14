<?php

namespace App\Http\Controllers;
use App\Models\Inventario;
use App\Models\ListadoActividadPretratamiento;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ListadoActividadesPretratamientoController extends BaseController
{
    public function index(){
        // Primero obtendremos el array de los datos
        $Datos = DB::Select('SELECT LAP.idListadoActividadPretratamiento,
                                          A.NombreArea,
                                          AP.NombreAreaPretratamiento,
                                          NA.NombreActividad,
                                          CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                          CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                          E.NombreEstado,
                                          LAP.FechaCreacionActividad,  ' . '.
                                          LAP.FechaConclusionActividad
                                  FROM ListadoActividadPretratamiento As LAP
                                         INNER JOIN Area A
                                                    ON LAP.idArea = A.idArea
                                         INNER JOIN AreaPretratamiento AP
                                                    ON LAP.idAreaPretratamiento = AP.idAreaPretratamiento
                                         INNER JOIN NombreActividad NA
                                                    ON LAP.idNombreActividad = NA.idNombreActividad
                                         INNER JOIN users US
                                                    ON LAP.CreadoPor = US.idUsuario
                                         INNER JOIN users US2
                                                    ON LAP.RealizadoPor = US2.idUsuario
                                         INNER JOIN Estado E
                                                    ON LAP.EstadoActividad = E.idEstado
                                  WHERE LAP.EstadoActividad != 5
                                    AND LAP.EstadoActividad != 6
                                    AND LAP.EstadoActividad != 7;');

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
        $Datos = array("idArea"=>$request->idArea,
                       "idAreaPretratamiento"=>$request->idAreaPretratamiento,
                       "idNombreActividad"=>$request->idNombreActividad,
                       //"FechaCreacionActividad"=>$request->FechaCreacionActividad,
                       //"FechaConclusionActividad"=>$request->FechaConclusionActividad,
                       "EstadoActividad"=>$request->EstadoActividad,
                       "CreadoPor"=>$request->CreadoPor,
                       "RealizadoPor"=>$request->CreadoPor);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idArea" => 'required|integer',
                       "idAreaPretratamiento" => 'required|integer',
                       "idNombreActividad" => 'required|integer',
                       //"FechaCreacionActividad" => 'required|date',
                       //"FechaConclusionActividad" => 'required|date',
                       "EstadoActividad" => 'required|integer',
                       "CreadoPor" => 'required|integer',
                       "RealizadoPor" => 'required|integer'];

            $Mensajes = [
                         "idArea.required" => 'Es necesario registrar el área donde se realizará.',
                         "idAreaPretratamiento.required" => 'Es necesario registrar el área donde se realizará.',
                         "idNombreActividad.required" => 'Es necesario agregar un nombre de la actividad.',
                         //"FechaCreacionActividad.required" => 'Es necesario agregar la fecha en que se crea la actividad.',
                         //"FechaConclusionActividad.required" => 'Es necesario agregar un la fecha en que se concluye la actividad.',
                         "EstadoActividad.required" => 'Es necesario agregar un de la actividad.',
                         "CreadoPor.required" => 'Es necesario agregar un solicitante.',
                         "RealizadoPor.required" => 'Es necesario agregar un encargado de realización de la actividad.'];
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
                $ListadoActividadPretratamiento = new ListadoActividadPretratamiento();

                // Ingresamos los datos
                $ListadoActividadPretratamiento->idArea = $Datos["idArea"];
                $ListadoActividadPretratamiento->idAreaPretratamiento = $Datos["idAreaPretratamiento"];
                $ListadoActividadPretratamiento->idNombreActividad = $Datos["idNombreActividad"];
                $ListadoActividadPretratamiento->FechaCreacionActividad = date('Y-m-d H:i:s');
                $ListadoActividadPretratamiento->FechaConclusionActividad = date('Y-m-d H:i:s');
                $ListadoActividadPretratamiento->EstadoActividad = $Datos["EstadoActividad"];
                $ListadoActividadPretratamiento->CreadoPor = $Datos["CreadoPor"];
                $ListadoActividadPretratamiento->RealizadoPor = $Datos["RealizadoPor"];

                // Ejecutamos la acción de guardar el usuario
                $ListadoActividadPretratamiento->save();

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
        $Datos = array("idArea"=>$request->idArea,
            "idAreaPretratamiento"=>$request->idAreaPretratamiento,
            "idNombreActividad"=>$request->idNombreActividad,
            "FechaCreacionActividad"=>$request->FechaCreacionActividad,
            "FechaConclusionActividad"=>$request->FechaConclusionActividad,
            "EstadoActividad"=>$request->EstadoActividad,
            "CreadoPor"=>$request->CreadoPor,
            "RealizadoPor"=>$request->RealizadoPor);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idArea" => 'required|integer',
                "idAreaPretratamiento" => 'required|integer',
                "idNombreActividad" => 'required|integer',
                "FechaCreacionActividad" => 'required|date',
                "FechaConclusionActividad" => 'required|date',
                "EstadoActividad" => 'required|integer',
                "CreadoPor" => 'required|integer',
                "RealizadoPor" => 'required|integer'];

            $Mensajes = [
                "idArea.required" => 'Es necesario registrar el área donde se realizará.',
                "idAreaPretratamiento.required" => 'Es necesario registrar el área donde se realizará.',
                "idNombreActividad.required" => 'Es necesario agregar un nombre de la actividad.',
                "FechaCreacionActividad.required" => 'Es necesario agregar la fecha en que se crea la actividad.',
                "FechaConclusionActividad.required" => 'Es necesario agregar un la fecha en que se concluye la actividad.',
                "EstadoActividad.required" => 'Es necesario agregar un de la actividad.',
                "CreadoPor.required" => 'Es necesario agregar un solicitante.',
                "RealizadoPor.required" => 'Es necesario agregar un encargado de realización de la actividad.'];
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
                $ObtenerListadoActividadPretratamiento = ListadoActividadPretratamiento::where("idListadoActividadPretratamiento", $id)->get();

                if(!empty($ObtenerListadoActividadPretratamiento[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoActividadPretratamiento = ListadoActividadPretratamiento::where("idListadoActividadPretratamiento", $id)->update($Datos);

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
        $ListadoActividadPretratamiento = DB::SELECT('SELECT LAP.idListadoActividadPretratamiento,
                                                                   A.NombreArea,
                                                                   AP.NombreAreaPretratamiento,
                                                                   NA.NombreActividad,
                                                                   CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                                                   CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                                                   E.NombreEstado,
                                                                   E.idEstado,
                                                                   LAP.FechaCreacionActividad,
                                                                   LAP.FechaConclusionActividad ' . '
                                                            FROM ListadoActividadPretratamiento As LAP
                                                                     INNER JOIN Area A
                                                                                ON LAP.idArea = A.idArea
                                                                     INNER JOIN AreaPretratamiento AP
                                                                                ON LAP.idAreaPretratamiento = AP.idAreaPretratamiento
                                                                     INNER JOIN NombreActividad NA
                                                                                ON LAP.idNombreActividad = NA.idNombreActividad
                                                                     INNER JOIN users US
                                                                                ON LAP.CreadoPor = US.idUsuario
                                                                     INNER JOIN users US2
                                                                                ON LAP.RealizadoPor = US2.idUsuario
                                                                     INNER JOIN Estado E
                                                                                ON LAP.EstadoActividad = E.idEstado
                                                                     WHERE idListadoActividadPretratamiento = ' . $id . ';');
        // Verificamos que el array no esté vacio
        if ($ListadoActividadPretratamiento != "[]" && !empty($ListadoActividadPretratamiento)) {
            $json = array(
                'status' => 200,
                'detalle' => $ListadoActividadPretratamiento
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

    public function cambiarEstadoActividadPretratamiento($id, Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("EstadoActividad"=>$request->EstadoActividad,
                       "RealizadoPor"=>$request->RealizadoPor,
                       "FechaConclusionActividad"=>date('Y-m-d H:i:s'));

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["EstadoActividad" => 'required|integer',
                       "RealizadoPor" => 'required|integer'];

            $Mensajes = ["EstadoActividad.required" => 'Es necesario agregar un estado de la actividad.'];
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
                $ObtenerListadoActividadPretratamiento = ListadoActividadPretratamiento::where("idListadoActividadPretratamiento", $id)->get();

                if(!empty($ObtenerListadoActividadPretratamiento[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoActividadPretratamiento = ListadoActividadPretratamiento::where("idListadoActividadPretratamiento", $id)->update($Datos);

                    // Si estamos cancelando la actividad debemos devolver todos los productos.
                    if($Datos["EstadoActividad"] === 6){
                        $this->devolucionProductosInventario($id);
                    }
                    else if($Datos["EstadoActividad"] === 5){
                        $this->liberacionProductoFlotante($id);
                    }

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

    public function devolucionProductosInventario($id){
        Inventario::where("idListadoActividadPretratamiento", $id)
            ->update([
                'CantidadExistencia' => 0.0,
                'ProductoFlotante' => 0.0
            ]);
    }

    public function liberacionProductoFlotante($id){
        Inventario::where("idListadoActividadPretratamiento", $id)
            ->update([
                'ProductoFlotante' => 0.0
            ]);
    }

    public function listadoActividadesGeneralPretratamiento(){
        // Primero obtendremos el array de los datos
        $Datos = DB::Select('SELECT LAP.idListadoActividadPretratamiento,
                                          A.NombreArea,
                                          AP.NombreAreaPretratamiento,
                                          NA.NombreActividad,
                                          CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                          CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                          E.NombreEstado,
                                          LAP.FechaCreacionActividad,  ' . '.
                                          LAP.FechaConclusionActividad
                                  FROM ListadoActividadPretratamiento As LAP
                                         INNER JOIN Area A
                                                    ON LAP.idArea = A.idArea
                                         INNER JOIN AreaPretratamiento AP
                                                    ON LAP.idAreaPretratamiento = AP.idAreaPretratamiento
                                         INNER JOIN NombreActividad NA
                                                    ON LAP.idNombreActividad = NA.idNombreActividad
                                         INNER JOIN users US
                                                    ON LAP.CreadoPor = US.idUsuario
                                         INNER JOIN users US2
                                                    ON LAP.RealizadoPor = US2.idUsuario
                                         INNER JOIN Estado E
                                                    ON LAP.EstadoActividad = E.idEstado;');

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

    public function listadoActividadesPorFecha(Request $request){
        // Inicializamos una variable para almacenar un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("FechaInicial"=>$request->FechaInicial,
                       "FechaFinal"=>$request->FechaFinal);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["FechaInicial" => 'required',
                       "FechaFinal" => 'required'];

            $Mensajes = [
                         "FechaInicial.required" => 'Es necesario marcar una fecha inicial.',
                         "FechaFinal.required" => 'Es necesario marcar una fecha final.'];
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
            }else {
                // Ordenaremos las fechas en caso estar al revés
                if($Datos["FechaInicial"] > $Datos["FechaFinal"]){
                    $temporal = $Datos["FechaInicial"];
                    $Datos["FechaInicial"] = $Datos["FechaFinal"];
                    $Datos["FechaFinal"] = $temporal;
                }
                // Primero obtendremos el array de los datos
                $Datos = DB::Select('SELECT LAP.idListadoActividadPretratamiento AS CodigoActividad,
                                          A.NombreArea,
                                          AP.NombreAreaPretratamiento,
                                          NA.NombreActividad,
                                          CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                          CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                          E.NombreEstado,
                                          LAP.FechaCreacionActividad,  ' . '.
                                          LAP.FechaConclusionActividad
                                  FROM ListadoActividadPretratamiento As LAP
                                         INNER JOIN Area A
                                                    ON LAP.idArea = A.idArea
                                         INNER JOIN AreaPretratamiento AP
                                                    ON LAP.idAreaPretratamiento = AP.idAreaPretratamiento
                                         INNER JOIN NombreActividad NA
                                                    ON LAP.idNombreActividad = NA.idNombreActividad
                                         INNER JOIN users US
                                                    ON LAP.CreadoPor = US.idUsuario
                                         INNER JOIN users US2
                                                    ON LAP.RealizadoPor = US2.idUsuario
                                         INNER JOIN Estado E
                                                    ON LAP.EstadoActividad = E.idEstado
                                                    WHERE CAST(LAP.FechaCreacionActividad AS DATE)
                                                    BETWEEN "' . $Datos["FechaInicial"] .'"
                                                        AND
                                                        "' . $Datos["FechaFinal"] .'";');

                // Verificamos que el array no esté vacio
                if (!empty($Datos[0])) {
                    $json = array(
                        'status' => 200,
                        'total' => count($Datos),
                        'detalle' => $Datos
                    );
                } else {
                    $json = array(
                        'status' => 200,
                        'total' => 0,
                        'detalle' => "No hay registros"
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
}
