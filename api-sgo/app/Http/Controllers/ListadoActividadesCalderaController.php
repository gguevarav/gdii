<?php

namespace App\Http\Controllers;
use App\Models\ListadoActividad;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ListadoActividadCaldera;
use App\Models\Inventario;

class ListadoActividadesCalderaController extends BaseController
{
    public function index(){
        // Primero obtendremos el array de los datos
        $Datos = DB::Select('SELECT LAC.idListadoActividadCaldera,
                                   A.NombreArea,
                                   C.NombreCaldera,
                                   AC.NombreAreaCaldera,
                                   NA.NombreActividad,
                                   CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                   CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                   E.NombreEstado,
                                   LAC.FechaCreacionActividad, ' . '
                                   LAC.FechaConclusionActividad
                            FROM ListadoActividadCaldera As LAC
                                     INNER JOIN Area A
                                                ON LAC.idArea = A.idArea
                                     INNER JOIN Caldera C
                                                ON LAC.idCaldera = C.idCaldera
                                     INNER JOIN AreaCaldera AC
                                                ON LAC.idAreaCaldera = AC.idAreaCaldera
                                     INNER JOIN NombreActividad NA
                                                ON LAC.idNombreActividad = NA.idNombreActividad
                                     INNER JOIN users US
                                                ON LAC.CreadoPor = US.idUsuario
                                     INNER JOIN users US2
                                                ON LAC.RealizadoPor = US2.idUsuario
                                     INNER JOIN Estado E
                                                ON LAC.EstadoActividad = E.idEstado
                            WHERE LAC.EstadoActividad != 5
                            AND LAC.EstadoActividad != 6
                            AND LAC.EstadoActividad != 7;');

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
            "idCaldera"=>$request->idCaldera,
            "idAreaCaldera"=>$request->idAreaCaldera,
            "idNombreActividad"=>$request->idNombreActividad,
            //"FechaCreacionActividad"=>$request->FechaCreacionActividad,
            //"FechaConclusionActividad"=>$request->FechaConclusionActividad,
            "EstadoActividad"=>$request->EstadoActividad,
            "CreadoPor"=>$request->CreadoPor,
            "RealizadoPor"=>$request->RealizadoPor);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idArea" => 'required|integer',
                "idCaldera" => 'required|integer',
                "idAreaCaldera" => 'required|integer',
                "idNombreActividad" => 'required|integer',
                //"FechaCreacionActividad" => 'required|date',
                //"FechaConclusionActividad" => 'required|date',
                "EstadoActividad" => 'required|integer',
                "CreadoPor" => 'required|integer',
                "RealizadoPor" => 'required|integer'];

            $Mensajes = [
                "idArea.required" => 'Es necesario agregar un área donde se realizará.',
                "idCaldera.required" => 'Es necesario registrar la caldera donde se realizará.',
                "idAreaCaldera.required" => 'Es necesario agregar un subárea donde se realizará.',
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
                $ListadoActividadCaldera = new ListadoActividadCaldera();

                // Ingresamos los datos
                $ListadoActividadCaldera->idArea = $Datos["idArea"];
                $ListadoActividadCaldera->idCaldera = $Datos["idCaldera"];
                $ListadoActividadCaldera->idAreaCaldera = $Datos["idAreaCaldera"];
                $ListadoActividadCaldera->idNombreActividad = $Datos["idNombreActividad"];
                $ListadoActividadCaldera->FechaCreacionActividad = date('Y-m-d H:i:s');
                $ListadoActividadCaldera->FechaConclusionActividad = date('Y-m-d H:i:s');
                $ListadoActividadCaldera->EstadoActividad = $Datos["EstadoActividad"];
                $ListadoActividadCaldera->CreadoPor = $Datos["CreadoPor"];
                $ListadoActividadCaldera->RealizadoPor = $Datos["RealizadoPor"];

                // Ejecutamos la acción de guardar el usuario
                $ListadoActividadCaldera->save();

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
            "idCaldera"=>$request->idCaldera,
            "idAreaCaldera"=>$request->idAreaCaldera,
            "idNombreActividad"=>$request->idNombreActividad,
            //"FechaCreacionActividad"=>$request->FechaCreacionActividad,
            "FechaConclusionActividad"=>date('Y-m-d H:i:s'),
            "EstadoActividad"=>$request->EstadoActividad,
            "CreadoPor"=>$request->CreadoPor,
            "RealizadoPor"=>$request->RealizadoPor);

        // Validamos que los Datos no estén vacios
        if(!empty($Datos)){
            // Separamos la validación
            // Reglas
            $Reglas = ["idArea" => 'required|integer',
                       "idCaldera" => 'required|integer',
                       "idAreaCaldera" => 'required|integer',
                       "idNombreActividad" => 'required|integer',
                       //"FechaCreacionActividad" => 'required|date',
                       //"FechaConclusionActividad" => 'required|date',
                       "EstadoActividad" => 'required|integer',
                       "CreadoPor" => 'required|integer',
                       "RealizadoPor" => 'required|integer'];

            $Mensajes = [
                         "idArea.required" => 'Es necesario agregar un área donde se realizará.',
                         "idCaldera.required" => 'Es necesario registrar la caldera donde se realizará.',
                         "idAreaCaldera.required" => 'Es necesario agregar un subárea donde se realizará.',
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
                // Obtendremos el ListadoActividad de la base de datos
                $ObtenerListadoActividadCaldera = ListadoActividadCaldera::where("idListadoActividadCaldera", $id)->get();

                if(!empty($ObtenerListadoActividadCaldera[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoActividadCaldera = ListadoActividadCaldera::where("idListadoActividadCaldera", $id)->update($Datos);

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
        $ListadoActividadCaldera = DB::SELECT('SELECT LAC.idListadoActividadCaldera,
                                   A.NombreArea,
                                   C.NombreCaldera,
                                   AC.NombreAreaCaldera,
                                   NA.NombreActividad,
                                   E.idEstado,
                                   CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                   CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                   E.NombreEstado,
                                   LAC.FechaCreacionActividad, ' . '
                                   LAC.FechaConclusionActividad
                            FROM ListadoActividadCaldera As LAC
                                     INNER JOIN Area A
                                                ON LAC.idArea = A.idArea
                                     INNER JOIN Caldera C
                                                ON LAC.idCaldera = C.idCaldera
                                     INNER JOIN AreaCaldera AC
                                                ON LAC.idAreaCaldera = AC.idAreaCaldera
                                     INNER JOIN NombreActividad NA
                                                ON LAC.idNombreActividad = NA.idNombreActividad
                                     INNER JOIN users US
                                                ON LAC.CreadoPor = US.idUsuario
                                     INNER JOIN users US2
                                                ON LAC.RealizadoPor = US2.idUsuario
                                     INNER JOIN Estado E
                                                ON LAC.EstadoActividad = E.idEstado
                                     WHERE LAC.idListadoActividadCaldera = ' . $id . ';');
        // Verificamos que el array no esté vacio
        if ($ListadoActividadCaldera != "[]" && !empty($ListadoActividadCaldera)) {
            $json = array(
                'status' => 200,
                'detalle' => $ListadoActividadCaldera
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

    public function cambiarEstadoActividadCaldera($id, Request $request){
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
                $ObtenerListadoActividadCaldera = ListadoActividadCaldera::where("idListadoActividadCaldera", $id)->get();

                if(!empty($ObtenerListadoActividadCaldera[0])){
                    // Modificamos la información, pasamos la información contenida
                    // en el array de los datos
                    $ListadoActividadCaldera = ListadoActividadCaldera::where("idListadoActividadCaldera", $id)->update($Datos);

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
        Inventario::where("idListadoActividadCaldera", $id)
                    ->update([
                             'CantidadExistencia' => 0.0,
                             'ProductoFlotante' => 0.0
                            ]);
    }

    public function liberacionProductoFlotante($id){
        Inventario::where("idListadoActividadCaldera", $id)
                    ->update([
                        'ProductoFlotante' => 0.0
                    ]);
    }

    public function listadoActividadesGeneralCaldera(){
        // Primero obtendremos el array de los datos
        $Datos = DB::Select('SELECT LAC.idListadoActividadCaldera,
                                   A.NombreArea,
                                   C.NombreCaldera,
                                   AC.NombreAreaCaldera,
                                   NA.NombreActividad,
                                   CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                   CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                   E.NombreEstado,
                                   LAC.FechaCreacionActividad, ' . '
                                   LAC.FechaConclusionActividad
                            FROM ListadoActividadCaldera As LAC
                                     INNER JOIN Area A
                                                ON LAC.idArea = A.idArea
                                     INNER JOIN Caldera C
                                                ON LAC.idCaldera = C.idCaldera
                                     INNER JOIN AreaCaldera AC
                                                ON LAC.idAreaCaldera = AC.idAreaCaldera
                                     INNER JOIN NombreActividad NA
                                                ON LAC.idNombreActividad = NA.idNombreActividad
                                     INNER JOIN users US
                                                ON LAC.CreadoPor = US.idUsuario
                                     INNER JOIN users US2
                                                ON LAC.RealizadoPor = US2.idUsuario
                                     INNER JOIN Estado E
                                                ON LAC.EstadoActividad = E.idEstado;');

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
                $Datos = DB::Select('SELECT LAC.idListadoActividadCaldera AS CodigoActividad,
                                       A.NombreArea,
                                       C.NombreCaldera,
                                       AC.NombreAreaCaldera,
                                       NA.NombreActividad,
                                       CONCAT(US.NombreUsuario, " ", US.ApellidoUsuario) AS RealizadoPor,
                                       CONCAT(US2.NombreUsuario, " ", US2.ApellidoUsuario) AS CreadoPor,
                                       E.NombreEstado,
                                       LAC.FechaCreacionActividad,
                                       LAC.FechaConclusionActividad ' . '
                                FROM ListadoActividadCaldera As LAC

                                         INNER JOIN Area A
                                                    ON LAC.idArea = A.idArea
                                         INNER JOIN Caldera C
                                                    ON LAC.idCaldera = C.idCaldera
                                         INNER JOIN AreaCaldera AC
                                                    ON LAC.idAreaCaldera = AC.idAreaCaldera
                                         INNER JOIN NombreActividad NA
                                                    ON LAC.idNombreActividad = NA.idNombreActividad
                                         INNER JOIN users US
                                                    ON LAC.CreadoPor = US.idUsuario
                                         INNER JOIN users US2
                                                    ON LAC.RealizadoPor = US2.idUsuario
                                         INNER JOIN Estado E
                                                    ON LAC.EstadoActividad = E.idEstado

                                                    WHERE CAST(LAC.FechaCreacionActividad AS DATE)
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
