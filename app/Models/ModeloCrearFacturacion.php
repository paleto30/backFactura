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

    

    /**  
     *       funcion que me permite realizar una facturacion 
     *      esta funcion recibe todos los datos , y utiliza las diferentes funciones de los modelos
     *       para realizar los guardados de los datos en la base de datos
     */    
    public function crearFacturacionDb()
    {
        
        try {

            $db = new Conexion;


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
            

            $cliente = new ClienteModel($this->dataCliente['cedula'],$this->dataCliente['nombre'],$this->dataCliente['correo'],$this->dataCliente['direccion'],$this->dataCliente['telefono']);
            $clienteCreado = $cliente->addCliente();

            if (empty($clienteCreado)) {
                return ['message' => 'Algo ha salido Mal, revisa  la informacion que intentas registrar'];
            }

            $factura = new FacturaModel($this->dataFactura['codigo_factura'],$this->dataFactura['nombre_vendedor'],$this->dataFactura['fecha'], $clienteCreado[0]['id']);
            $facturaCreada = $factura->insertAbill();

            if (empty($facturaCreada)) {
                return ['message' => 'Error en la creacion de la factura'];
            }

            foreach ($this->productos as $key) {
                
                $query = "select id, stock from productos where cod_producto = ?";
                $stament = $db->connect()->prepare($query);
                $stament->execute([$key['cod_producto']]);
                $product = $stament->fetchAll(PDO::FETCH_ASSOC);
                $detalle = new DetalleFacturaModel($facturaCreada[0]['id'],$product[0]['id'],$key['cantidad']);

                $product[0]['stock'] -= $key['cantidad'];
                $queryUpdate = "UPDATE productos SET stock = ? WHERE id = ?";
                $staments = $db->connect()->prepare($queryUpdate); 
                $staments->execute([$product[0]['stock'], $product[0]['id']]);
                $detalle->save();
            
            }

            $db->closedCon();
            return [
                $clienteCreado[0],
                $facturaCreada[0],
                $this->productos
            ];


        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }


}


?>