<?php

namespace App\Models;

use Config\Database\Conexion;
use Exception;
use PDO;

class ProductoModel
{


    public function __construct(private $cod_producto, private $nombre_producto, private $stock, private $valor_unidad)
    {
        $this->cod_producto = $cod_producto;
        $this->nombre_producto = $nombre_producto;
        $this->stock = $stock;
        $this->valor_unidad = $valor_unidad;        
    }


    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
                $this->$name = $value;
        }
        throw new Exception("GET  Propiedad invalida: " . $name);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new Exception("SET  Propiedad invalida: " . $name);
    }

    // esta funcion me retorna los parametros del cliente
    public function toString()
    {
        return [
            'cod_producto'     => $this->cod_producto,
            'nombre_producto'  => $this->nombre_producto,
            'stock'            => $this->stock,
            'valor_unidad'     => $this->valor_unidad,
        ];
    }



    public static function getAllProducts()
    {
        try {
            $db = new Conexion;
            $query = "SELECT * FROM productos";
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



    public static function getOneProductById($id)
    {
        try {
            
            $db = new Conexion;
            $query = "select * from productos where id = ?";
            $stament = $db->connect()->prepare($query);            
            $stament->execute([$id]);
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();

            if (empty($result)) return ['message' => 'No existe este registro'];
            return  ['data' => $result]; 

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }




    public function getProductoByCode($code){
        try {

            $db = new Conexion;
            $queryValidated = "select * from productos where cod_producto = ?";
            $stament = $db->connect()->prepare($queryValidated); 
            $stament->execute([$code]);
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /* 
            insertar un producto
    */
    public  function insertProduct()
    {
        try {
            $db = new Conexion;
            $productExist = $this->getProductoByCode($this->cod_producto);

            if (empty($productExist)) {
                $query = "INSERT INTO productos (cod_producto, nombre_producto, stock, valor_unidad) values ( ?, ?, ?, ?)";
                $stament = $db->connect()->prepare($query);
                $stament->execute([$this->cod_producto,$this->nombre_producto,$this->stock,$this->valor_unidad]); 
                $newProduct = $this->getProductoByCode($this->cod_producto);
                $db->closedCon();
                return ['message' => 'Product Created', 'data' => $newProduct]; 
            }

            return [
                'message' => 'Codigo de Producto ya registrado, el codigo de Producto debe ser unico',
                "data"    => $productExist
            ];

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }




    /**
     *  
     *          funcion para cargar el producto en los inputs cuando existe  
     * 
    */
    public static function getProductoInDb($cod_producto)
    {
        try {

            $db = new Conexion;
            $query = "select id , cod_producto, nombre_producto, valor_unidad from productos where cod_producto = ?"; 
            $stament = $db->connect()->prepare($query);
            $stament->execute([$cod_producto]);
            $producto = $stament->fetch(PDO::FETCH_ASSOC); 
            $db->closedCon();
            if (empty($producto)) {
                return ['data'=> 'Inexistente'];
            }

            return $producto;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }



}

?>