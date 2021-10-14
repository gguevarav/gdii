<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class InicioSesionController extends BaseController
{
    public function iniciosesion(Request $request){
        // Inicio de sesión primero obtendermos el token de autorización que nos están enviando
        // Obtendremos todos los usuarios
        $Usuarios = Usuario::all();
        // Guardamos el token
        $token = $request->header('Authorization');
        // Buscamos el usuario
        foreach($Usuarios as $key => $value){
            if("Basic " . base64_encode($value["CorreoUsuario"].":".$value["ContraseniaUsuario"]) == $token){
                $json = array(
                    "status" => "202", 
                    "detalle" => "Inicio de sesion correcto",
                    "claveAutorizacion" => Hash::make($value["CorreoUsuario"].":".$value["ContraseniaUsuario"])
                );
            }else{
                $json = array(
                    "status" => "404", 
                    "detalle" => "Inicio de sesion incorrecto"
                );
            }
        }
        // Devolvemos la respuesta en un Json
        return response()->json($json);
    }
}