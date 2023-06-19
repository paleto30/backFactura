<?php

namespace App\Models;

use Config\Database\Conexion;
use PDO;

class ModeloCrearFacturacion{
    


    public function __construct(private $dataFactura, private $dataCliente, private $productos)
    {
        $this->dataFactura = $dataFactura;
        $this->dataCliente = $dataCliente;
        $this->productos = $productos;
    }   


    /*   
     *     
     *      esta funcion me retorna los datos de cada 
     *      seccion que se va a agregar
     * 
     */
    public function toString()
    {
        return [
            'data factura' => $this->dataFactura,
            'data cliente' => $this->dataCliente,
            'data product' => $this->productos 
        ];
    }


    /* 
            funcion me permite validar por la cedula
            que el cliente no este registrado  
    */ 
    private function validarCliente($cedula){
        try {

            $db = new Conexion;
            $query = "select * from clientes where cedula = ?";
            $stament = $db->connect()->prepare($query);    
            $stament->execute([$cedula]);
            $clienteExiste = $stament->fetch(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($clienteExiste))  return false; // si el cliente no existe retorna falso, (osea que no existe)

            return true;// si el cliente existe entonces returna true (osea que existe)
        } catch (\Exception $e) {
            return  $e->getMessage();
        }
    }
    

    /* 
            funcion para obtener los datos del cliente existente 
    */
    private function obtenerCliente($cedula){
        try {
            $db = new Conexion;
            $query = "select * from clientes where cedula = ?";
            $sta = $db->connect()->prepare($query);
            $sta->execute([$cedula]);
            $cliente = $sta->fetch(PDO::FETCH_ASSOC);
            $db->closedCon();
            return $cliente;
        } catch (\Exception $e) {
            return  $e->getMessage();
        }
    }


    /*  
            funcion para validar la que la factura no haya sido 
            creada antes, si esta ya fue creada antes entonces
            no se podra registrar
    */
    public function validarFactura($codigoFact){
        try {

            $db = new Conexion;
            $query = "select * from facturas where codigo_factura = ?";
            $stament = $db->connect()->prepare($query);    
            $stament->execute([$codigoFact]);
            $facturaExiste = $stament->fetch(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($facturaExiste))  return false;  // si el cliente no existe retorna falso, (osea que no existe)
               
            return true;  // si el cliente existe entonces returna true (osea que existe)
        } catch (\Exception $e) {
            return  $e->getMessage();
        }
    }


    /**  
     *       funcion encargada de usar todas las funciones de validacion anteriores 
     *       una vez se hacen todass las validaciones esta es la funcion
     *       que me va a permitir crear un registro de una facturacion,
     *       y va a registrar los datos en las respectivas tablas que esten 
     *       implicadas en la accion de una facturacion 
     */
    public function crearFacturacionDb()
    {
        
        try {

            $db = new Conexion;


            // for para validar que los productos tengas todas sus llaves y valores con datos
            foreach ($this->productos as $key) {
                foreach ($key as $k => $v) {
                    if (empty($k) || empty($v)) {
                        return [
                            'ERROR' => "NI LA LLAVE NI EL VALOR DEL 'PRODUCTO' PUEDEN SER VACIOS!"
                        ];
                    }
                }
            }

            // for para validar que las llaves del cod_producto no tengan un valor
            // repetido 
            $valoresCod_producto = [];
            foreach ($this->productos as $key) {
                foreach ($key as $k => $v) {
                    if($k === 'cod_producto'){
                        if (in_array($v,$valoresCod_producto)) {
                            return [
                                'error' => "los valores de cod_producto  no deben repetirse, Puedes aumentar la cantidad sin necesidad de registrarlo dos veces",
                                'message' => "campo duplicado en cod_producto: $v"
                            ];
                        }
                        $valoresCod_producto[] = $v;
                    }
                }
            }


            // for  para la validacion de la existencia y stock de los productos 
            foreach ($this->productos as $key) {
                 
                $queryV = "select * from productos where cod_producto = ?";
                $stament = $db->connect()->prepare($queryV);
                $stament->execute([$key['cod_producto']]);
                $producto = $stament->fetchAll(PDO::FETCH_ASSOC);              
                if (empty($producto)) {
                    return [
                        'message' => 'No existe el producto: '.$key['cod_producto']
                    ];
                }elseif ($producto[0]['stock'] < $key['cantidad']) {
                    return [
                        'message' => 'No hay esa cantidad del producto: '.$key['cod_producto'],
                        'cantidad disponible' => $producto[0]['stock']  
                    ];
                }
            }
            
            // for para validar que los campos de la factura no vengan vacios
            foreach ($this->dataFactura as $key => $value) {
                if (empty($value)) {
                    return ['message' => 'Campos de la factura vacios'];
                }
            }
            
            // for para validad que los campos del cliente no vengan vacios
            foreach ($this->dataCliente as $key => $value) {
                if (empty($value)) {
                    return ['message' => 'Campos del cliente vacios'];
                }
            }

            // rompemos si la facttura ya existe , debido a que  no pueden haber dos facturas con el mismo codigo 
            if ($this->validarFactura($this->dataFactura['codigo_factura'])) {
                return ['error' => 'El codigo de esta factura ya existe, no pueden existir dos facturas con el mismo codigo'];
            }
            
            // si el cliente ya existe entonces , no se crea un nuevo cliente
            // se hace la factura con los datos del cliente existente
            if ($this->validarCliente($this->dataCliente['cedula'])) {
                $clienteCreado = $this->obtenerCliente($this->dataCliente['cedula']);
                // creamos la factura
                $factura = new FacturaModel($this->dataFactura['codigo_factura'],$this->dataFactura['nombre_vendedor'],$this->dataFactura['fecha'], $clienteCreado['id']);
                $newFactura = $factura->insertAbill();

            }else{

                // creamos el cliente y pasamos los campos correspondientes
                $cliente = new ClienteModel($this->dataCliente['cedula'],$this->dataCliente['nombre'],$this->dataCliente['correo'],$this->dataCliente['direccion'],$this->dataCliente['telefono']);
                $clienteCreado = $cliente->addCliente();
                
                // creamos la factura
                $factura = new FacturaModel($this->dataFactura['codigo_factura'],$this->dataFactura['nombre_vendedor'],$this->dataFactura['fecha'], $clienteCreado['id']);
                $newFactura = $factura->insertAbill();
   
            }
            
            
            foreach ($this->productos as $key) {
                
                $query = "select id, stock from productos where cod_producto = ?";
                $stament = $db->connect()->prepare($query);
                $stament->execute([$key['cod_producto']]);
                $product = $stament->fetch(PDO::FETCH_ASSOC);
                $detalle = new DetalleFacturaModel($newFactura['id'],$product['id'],$key['cantidad']);

                $product['stock'] -= $key['cantidad'];
                $queryUpdate = "UPDATE productos SET stock = ? WHERE id = ?";
                $staments = $db->connect()->prepare($queryUpdate); 
                $staments->execute([$product['stock'], $product['id']]);
                $detalle->save();
            }

            $db->closedCon();
            return [
                'datos Reegistrados' => [
                    $newFactura,$clienteCreado,$this->productos
                ]
            ];


        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }


}


?>