<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        // Página principal
        Route::get('/', 'PrincipalController@index');
    });
});

Route::group([
    'middleware' => 'auth:api'
], function() {
    // Página principal
    Route::get('/', 'PrincipalController@index');

    // CRUD Unidades de Medida
    Route::get('/unidadesmedida', 'UnidadesMedidaController@index');
    Route::post('/unidadesmedida', 'UnidadesMedidaController@store');
    Route::delete('/unidadesmedida/{id}', 'UnidadesMedidaController@destroy');
    Route::put('/unidadesmedida/{id}', 'UnidadesMedidaController@update');
    Route::get('/unidadesmedida/{id}', 'UnidadesMedidaController@show');



    // CRUD Puestos
    Route::get('/puestos', 'PuestosController@index');
    Route::post('/puestos', 'PuestosController@store');
    Route::delete('/puestos/{id}', 'PuestosController@destroy');
    Route::put('/puestos/{id}', 'PuestosController@update');
    Route::get('/puestos/{id}', 'PuestosController@show');



    // CRUD Roles
    Route::get('/roles', 'RolesController@index');
    Route::post('/roles', 'RolesController@store');
    Route::delete('/roles/{id}', 'RolesController@destroy');
    Route::put('/roles/{id}', 'RolesController@update');
    Route::get('/roles/{id}', 'RolesController@show');

    // CRUD Usuarios
    Route::get('/usuarios', 'UsuariosController@index');
    //Route::post('/usuarios', 'UsuariosController@store');
    Route::post('/usuarios', 'AuthController@signup');
    Route::delete('/usuarios/{id}', 'UsuariosController@destroy');
    Route::put('/usuarios/{id}', 'UsuariosController@update');
    Route::get('/usuarios/{id}', 'UsuariosController@show');
    Route::get('/rolusuario/{id}', 'UsuariosController@rolUsuario');

    // CRUD Areas
    Route::get('/areas', 'AreasController@index');
    Route::post('/areas', 'AreasController@store');
    Route::delete('/areas/{id}', 'AreasController@destroy');
    Route::put('/areas/{id}', 'AreasController@update');
    Route::get('/areas/{id}', 'AreasController@show');

    // CRUD NombreActividad

    Route::post('/nombreactividades', 'NombreActividadesController@store');
    Route::delete('/nombreactividades/{id}', 'NombreActividadesController@destroy');
    Route::put('/nombreactividades/{id}', 'NombreActividadesController@update');
    Route::get('/nombreactividades/{id}', 'NombreActividadesController@show');



    // CRUD ListadoMaterial
    Route::get('/listadomateriales', 'ListadoMaterialesController@index');
    Route::post('/listadomateriales', 'ListadoMaterialesController@store');
    Route::delete('/listadomateriales/{id}', 'ListadoMaterialesController@destroy');
    Route::put('/listadomateriales/{id}', 'ListadoMaterialesController@update');
    Route::get('/listadomateriales/{id}', 'ListadoMaterialesController@show');

});

// CRUD Inventarios

Route::get('/inventario', 'InventarioController@index');
Route::post('/inventario', 'InventarioController@store');
Route::delete('/inventario/{id}', 'InventarioController@destroy');
Route::put('/inventario/{id}', 'InventarioController@update');
Route::get('/inventario/{id}', 'InventarioController@show');
Route::get('/productonoinventariado','InventarioController@productoNoInventariado');
Route::get('/productoinventariado','InventarioController@productoInventariado');
Route::post('/agregaracantidadinventario','InventarioController@agregarCantidadInventario');
Route::post('/descontarproductosinventario','InventarioController@descontarProductosInventario');

// CRUD Productos
Route::get('/productos', 'ProductosController@index');
Route::post('/productos', 'ProductosController@store');
Route::delete('/productos/{id}', 'ProductosController@destroy');
Route::put('/productos/{id}', 'ProductosController@update');
Route::get('/productos/{id}', 'ProductosController@show');


Route::get('/nombreactividades', 'NombreActividadesController@index');

Route::get('/areascaldera', 'AreaCalderasController@index');
Route::get('/areaspretratamiento', 'AreasPretratamientoController@index');
Route::get('/calderas', 'CalderasController@index');

// CRUD Listado Actividad Caldera
Route::get('/listadoactividadescaldera', 'ListadoActividadesCalderaController@index');
Route::post('/listadoactividadescaldera', 'ListadoActividadesCalderaController@store');
Route::delete('/listadoactividadescaldera/{id}', 'ListadoActividadesCalderaController@destroy');
Route::put('/listadoactividadescaldera/{id}', 'ListadoActividadesCalderaController@update');
Route::get('/listadoactividadescaldera/{id}', 'ListadoActividadesCalderaController@show');
Route::post('/cambiarestadoactividadcaldera/{id}', 'ListadoActividadesCalderaController@cambiarEstadoActividadCaldera');
Route::get('/listadoactividadesgeneralcaldera', 'ListadoActividadesCalderaController@listadoActividadesGeneralCaldera');
Route::post('/listadoactividadesporfechacaldera', 'ListadoActividadesCalderaController@listadoActividadesPorFecha');

// CRUD Listado Material Actividad Caldera
Route::get('/listadomaterialactividadescaldera', 'ListadoMaterialActividadesCalderaController@index');
Route::post('/listadomaterialactividadescaldera', 'ListadoMaterialActividadesCalderaController@store');
Route::delete('/listadomaterialactividadescaldera/{id}', 'ListadoMaterialActividadesCalderaController@destroy');
Route::put('/listadomaterialactividadescaldera/{id}', 'ListadoMaterialActividadesCalderaController@update');
Route::get('/listadomaterialactividadescaldera/{id}', 'ListadoMaterialActividadesCalderaController@show');

// CRUD Listado Actividad Pretratamiento
Route::get('/listadoactividadespretratamiento', 'ListadoActividadesPretratamientoController@index');
Route::post('/listadoactividadespretratamiento', 'ListadoActividadesPretratamientoController@store');
Route::delete('/listadoactividadespretratamiento/{id}', 'ListadoActividadesPretratamientoController@destroy');
Route::put('/listadoactividadespretratamiento/{id}', 'ListadoActividadesPretratamientoController@update');
Route::get('/listadoactividadespretratamiento/{id}', 'ListadoActividadesPretratamientoController@show');
Route::post('/cambiarestadoactividadpretratamiento/{id}', 'ListadoActividadesPretratamientoController@cambiarEstadoActividadPretratamiento');
Route::get('/listadoactividadesgeneralpretratamiento', 'ListadoActividadesPretratamientoController@listadoActividadesGeneralPretratamiento');
Route::post('/listadoactividadesporfechapretratamiento', 'ListadoActividadesPretratamientoController@listadoActividadesPorFecha');

// CRUD Listado Material Actividad Pretratamiento
Route::get('/listadomaterialactividadespretratamiento', 'ListadoMaterialActividadesPretratamientoController@index');
Route::post('/listadomaterialactividadespretratamiento', 'ListadoMaterialActividadesPretratamientoController@store');
Route::delete('/listadomaterialactividadespretratamiento/{id}', 'ListadoMaterialActividadesPretratamientoController@destroy');
Route::put('/listadomaterialactividadespretratamiento/{id}', 'ListadoMaterialActividadesPretratamientoController@update');
Route::get('/listadomaterialactividadespretratamiento/{id}', 'ListadoMaterialActividadesPretratamientoController@show');

// CRUD Listado Actividad Torre Enfriamiento
Route::get('/listadoactividadestorre', 'ListadoActividadesTorreEnfriamientoController@index');
Route::post('/listadoactividadestorre', 'ListadoActividadesTorreEnfriamientoController@store');
Route::delete('/listadoactividadestorre/{id}', 'ListadoActividadesTorreEnfriamientoController@destroy');
Route::put('/listadoactividadestorre/{id}', 'ListadoActividadesTorreEnfriamientoController@update');
Route::get('/listadoactividadestorre/{id}', 'ListadoActividadesTorreEnfriamientoController@show');
Route::post('/cambiarestadoactividadtorre/{id}', 'ListadoActividadesTorreEnfriamientoController@cambiarEstadoActividadTorreEnfriamiento');
Route::get('/listadoactividadesgeneraltorre', 'ListadoActividadesTorreEnfriamientoController@listadoActividadesGeneralTorreEnfriamiento');
Route::post('/listadoactividadesporfechatorre', 'ListadoActividadesTorreEnfriamientoController@listadoActividadesPorFecha');


// CRUD Listado Material Actividad Torre de Enfriamiento
Route::get('/listadomaterialactividadestorre', 'ListadoMaterialActividadesTorreEnfriamientoController@index');
Route::post('/listadomaterialactividadestorre', 'ListadoMaterialActividadesTorreEnfriamientoController@store');
Route::delete('/listadomaterialactividadestorre/{id}', 'ListadoMaterialActividadesTorreEnfriamientoController@destroy');
Route::put('/listadomaterialactividadestorre/{id}', 'ListadoMaterialActividadesTorreEnfriamientoController@update');
Route::get('/listadomaterialactividadestorre/{id}', 'ListadoMaterialActividadesTorreEnfriamientoController@show');
