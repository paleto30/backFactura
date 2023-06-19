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
                
                $query = "select id from productos where cod_producto = ?";
                $stament = $db->connect()->prepare($query);
                $stament->execute([$key['cod_producto']]);
                $result = $stament->fetchAll(PDO::FETCH_ASSOC);
                $detalle = new DetalleFacturaModel($facturaCreada[0]['id'],$result[0]['id'],$key['cantidad']);
                $detalle->save();
            }

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