<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signUp(Request $request)
    {
        // Creamos un json nulo
        $json = null;
        // Recogemos los Datos que almacenaremos, los ingresamos a un array
        $Datos = array("NombreUsuario"=>$request->NombreUsuario,
            "ApellidoUsuario"=>$request->ApellidoUsuario,
            "idPuesto"=>$request->idPuesto,
            "email"=>$request->email,
            "password"=>$request->password,
            "idRol"=>$request->idRol,
            "EstadoUsuario"=>$request->EstadoUsuario);

        // Separamos la validación
        // Reglas
        $Reglas = [
            "NombreUsuario" => 'required|string|max:255',
            "ApellidoUsuario" => 'required|string|max:255',
            "idPuesto" => 'required|integer',
            //"email" => 'required|string|email|max:255|unique:Usuario',
            "password" => 'required|string|max:255',
            "idRol" => 'required|integer',
            "EstadoUsuario" => 'required|integer'];

        $Mensajes = [
            "NombreUsuario.required" => 'Es necesario agregar un nombre',
            "ApellidoUsuario.required" => 'Es necesario agregar un apellido',
            "idPuesto.required" => 'Es necesario agregar un puesto para el usuario',
            "email.required" => 'Es necesario agregar un correo',
            //"email.unique" => 'El correo ya está registrado',
            "password.required" => 'Es necesario agregar una contraseña',
            "idRol.required" => 'Es necesario agregar un rol de usuario',
            "EstadoUsuario.required" => 'Es necesario agregar un estado del usuario'];
        // Validamos los Datos antes de insertarlos en la base de Datos
        $validacion = Validator::make($Datos, $Reglas, $Mensajes);
        /*$request->validate([
            'NombreUsuario' => 'required|string',
            'ApellidoUsuario' => 'required|string',
            'email' => 'required|string|email|unique:users',
            "password" => 'required|string|max:255',
            "idPuesto" => 'required|integer',
            "idRol" => 'required|integer',
            "EstadoUsuario" => 'required|integer']);*/

        if($validacion->fails()){
            // Devolvemos el mensaje que falló la validación de Datos
            $json = array(
                "status" => 404,
                "detalle" => "Los registros tienen errores",
                "errores" => $validacion->errors()->all()
            );
        }else {
            User::create([
                'NombreUsuario' => $request->NombreUsuario,
                'ApellidoUsuario' => $request->ApellidoUsuario,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'idPuesto' => $request->idPuesto,
                'idRol' => $request->idRol,
                'EstadoUsuario' => $request->EstadoUsuario
            ]);
            $json = array(
                "status" => 200,
                "detalle" => "Registro exitoso",
                //"idCliente" => str_replace("$", "-", $idCliente),
                //"LlaveSecreta" => str_replace("$", "-", $LlaveSecreta)
            );
        }
        return response()->json($json);
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
