<?php

namespace App\Http\Controllers;

use App\Models\ModeloCrearFacturacion;

class CrearFacturacionController
{





    public function registrarFactura()
    {

        try {


            $datos = json_decode(file_get_contents('php://input'),true);
            
            $facturaData = [
                'codigo_factura' => $datos['codigo_factura'],
                'nombre_vendedor' => $datos['nombre_vendedor'],
                'fecha' => $datos['fecha']
            ];
            
            $clienteData = [
                'cedula' => $datos['cedula'],
                'nombre' => $datos['nombre'],
                'correo' => $datos['correo'],
                'direccion' => $datos['direccion'],
                'telefono' => $datos['telefono']
            ];
            
            $productos  = $datos['productos'];

            $crearFacturacion = new ModeloCrearFacturacion($facturaData,$clienteData,$productos);

            echo json_encode($crearFacturacion->crearFacturacionDb());


             
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    
    }












}