<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class PrincipalController extends BaseController
{
    public function index(){      
        // Mostraremos un listado de las rutas disponibles

        $listadoRutas = array(
                        //  UnidadesMedida
                        array("GET", "unidadesmedida", "unidadesmedida.index", "UnidadesMedidaController@index"),
                        array("POST", "unidadesmedida", "unidadesmedida.store", "UnidadesMedidaController@store"),
                        array("DELETE", "unidadesmedida/{id}", "unidadesmedida.destroy", "UnidadesMedidaController@destroy"),
                        array("PUT", "unidadesmedida/{id}", "unidadesmedida.update", "UnidadesMedidaController@update"),
                        array("GET", "unidadesmedida/{id}", "unidadesmedida.show", "UnidadesMedidaController@show"),
                        // Productos
                        array("GET", "productos", "Productos.index", "ProductosController@index"),
                        array("POST", "productos", "Productos.store", "ProductosController@store"),
                        array("DELETE", "productos/{id}", "Productos.destroy", "ProductosController@destroy"),
                        array("PUT", "productos/{id}", "productos.update", "ProductosController@update"),
                        array("GET", "productos/{id}", "productos.show", "ProductosController@show"),
                        // Puestos
                        array("GET", "puestos", "puestos.index", "PuestosController@index"),
                        array("POST", "puestos", "puestos.store", "PuestosController@store"),
                        array("DELETE", "puestos/{id}", "puestos.destroy", "PuestosController@destroy"),
                        array("PUT", "puestos/{id}", "puestos.update", "PuestosController@update"),
                        array("GET", "puestos/{id}", "puestos.show", "PuestosController@show"),
                        // Roles
                        array("GET", "roles", "roles.index", "RolesController@index"),
                        array("POST", "roles", "roles.store", "RolesController@store"),
                        array("DELETE", "roles/{id}", "roles.destroy", "RolesController@destroy"),
                        array("PUT", "roles/{id}", "roles.update", "RolesController@update"),
                        array("GET", "roles/{id}", "roles.show", "RolesController@show"),
                        // Usuarios
                        array("GET", "usuarios", "usuarios.index", "UsuariosController@index"),
                        array("POST", "usuarios", "usuarios.store", "UsuariosController@store"),
                        array("DELETE", "usuarios/{id}", "usuarios.destroy", "UsuariosController@destroy"),
                        array("PUT", "usuarios/{id}", "usuarios.update", "UsuariosController@update"),
                        array("GET", "usuarios/{id}", "usuarios.show", "UsuariosController@show")
                    );

        echo "<table style='width:100%'>";
        echo "<tr>";
            echo "<td width='10%'><h4>HTTP Method</h4></td>";
            echo "<td width='10%'><h4>URI</h4></td>";
            echo "<td width='10%'><h4>Name</h4></td>";
            echo "<td width='70%'><h4>Action</h4></td>";
        echo "</tr>";
        foreach ($listadoRutas as $value) {
            echo "<tr>";
                echo "<td>" . $value[0] . "</td>";
                echo "<td>" . $value[1]. "</td>";
                echo "<td>" . $value[2] . "</td>";
                echo "<td>" . $value[3] . "</td>";
            echo "</tr>";
        }
    echo "</table>";
    }
}