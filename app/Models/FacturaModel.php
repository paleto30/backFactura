<?php

namespace App\Models;


use Config\Database\Conexion;
use Exception;
use PDO;

class FacturaModel {



    public function __construct(private $codigo_factura, private $nombre_vendedor, private $fecha, private $id_cliente)
    {
        $this->codigo_factura = $codigo_factura;
        $this->nombre_vendedor = $nombre_vendedor;
        $this->fecha = $fecha;
        $this->id_cliente = $id_cliente;
    }

    /* 
            getters and setter de los atributos
    */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
                $this->$name = $value;
        }
        throw new Exception("Propiedad invalida: " . $name);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new Exception("Propiedad invalidaa: " . $name);
    }


    //      esta funcion me retorna el objeto
    public function toString()
    {
        return [
            'codigo_factura'    => $this->codigo_factura,
            'nombre_vendedor'   => $this->nombre_vendedor,
            'fecha'             => $this->fecha,
            'id_cliente'        => $this->id_cliente,
        ];
    }




    /*  
            this function -> retorna todos los registros de la factura
    */
    public static function getAllBill()
    {
        try {

            $db = new Conexion;
            $query = "SELECT * FROM facturas";
            $stament = $db->connect()->prepare($query);
            $stament->execute();
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($result)) {
                return [
                    'message' => 'No existen registros',
                    'data' => $result 
                ];
            }

            return ['data' => $result];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /* 
            funcion para obtener una factura por su campo:
            codigo_factura (debe ser unico)
    */
    public function getByCode($code)
    {
        try {
            $db = new Conexion;
            $queryValidator = "select * from facturas where codigo_factura = ? ";
            $stament = $db->connect()->prepare($queryValidator);
            $stament->execute([$code]);
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



    /* 
            this function -> agrega una nueva factura
    */
    public function insertAbill()
    {
        try {
            $db = new Conexion;

            $billExist = $this->getByCode($this->codigo_factura);
            
            if (empty($billExist)) {
                $query = "insert into facturas (codigo_factura, nombre_vendedor, id_cliente) values ( ?, ?, ?)";
                $stament = $db->connect()->prepare($query);
                $stament->execute([
                    $this->codigo_factura,
                    $this->nombre_vendedor,
                    //$this->fecha,
                    $this->id_cliente
                ]);
                $newBill = $this->getByCode($this->codigo_factura);
                $db->closedCon();
                return $newBill;
               /*  return [
                    'message' => 'Bill created',
                    "data"    => $newBill
                ]; */
            }
            return $billExist;
            /* return [
                'message' => 'Codigo de Factura ya registrado, el codigo de factura debe ser unico',
                "data"    => $billExist
            ]; */

        } catch (\Exception $e) {
            return $e->getMessage();
        }
            
    }



    /* 
            funcion para obtener una factura por id
    */
    public static function getOneBillById($id)
    {
        try {
            $db = new Conexion;
            $query = "select * from facturas where id = ?";
            $stament = $db->connect()->prepare($query);
            $stament->execute([$id]);
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($result)) return ['message' => 'No Existe']; //->retorno si no existes
        
            return ['data' => $result]; // -> retorno si existe
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



}