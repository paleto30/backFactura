<?php

namespace App\Models;

use Config\Database\Conexion;
use Exception;
use PDO;

class DetalleFacturaModel 
{


    public function __construct(private $id_factura, private $id_producto, private $cantidad)
    {
        $this->id_factura = $id_factura;
        $this->id_producto = $id_producto;
        $this->cantidad = $cantidad;
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
            'id_factura'    => $this->id_factura,
            'id_producto'   => $this->id_producto,
            'cantidad'      => $this->cantidad,
        ];
    }



    public static function getAlldetalles(){
        try {
            $db = new Conexion;
            $query = "SELECT * FROM detalle_factura";
            $stamento = $db->connect()->prepare($query);
            $stamento->execute();
            $res =  $stamento->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($res)) {
                return [
                    'message'  => 'No existen registros',
                    'data'     => $res
                ];
            }
            return ['data'=>$res];
        } catch (\Exception $th) {
            return $th->getMessage();
        }        
   }



   /**
    *       Funcion para obtener un registor por su id
    *
    *       @param id
   */
    public static function getOneById($id)
    {
        try {

            $db = new Conexion;
            $query = "select * from detalle_factura where id = ?";
            $stament = $db->connect()->prepare($query);
            $stament->execute([$id]);
            $result = $stament->fetchAll(PDO::FETCH_ASSOC); 
            $db->closedCon();
            if(empty($result)){ 
                return ['message'=>'No existe'];
            }
            return ['data'=> $result];
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }



    /**  
    *        funcion para validar que en una misma factura
    *        no se registre dos veces el mismp proucto
    *        esto por que el detalle factura 
    *        tiene un campo para registrar cantidad
    *        de un producto especifico,
    *        asi que no deberian regsistrarse nas de una vez
    *        el mismo codigo de produto, 
    *        lo que varia es la cantidad
    *       @param  id_factura 
    *       @param  id_producto
    */
    public function validate($id_f, $id_p)
    {
        try {
            
            $db = new Conexion;        
            $queryVlidator = "select * from detalle_factura where detalle_factura.id_factura = ?  AND detalle_factura.id_producto = ?";
            $stament = $db->connect()->prepare($queryVlidator);
            $stament->execute([$id_f,$id_p]);
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($result)) {
                return [];
            }

            return $result;
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }


    /**   
    *       funcion para guarda el registro de un detalle_factura
    *       @param  id_factura 
    *       @param  id_producto
    *       @param  cantidad (de dicho producto)
    */
    public function save()
    {
        try {
            $db =  new Conexion;
            
            $query = "insert into detalle_factura (id_factura, id_producto, cantidad) values ( ?, ?, ?)";
            $stament = $db->connect()->prepare($query);
            $stament->execute([$this->id_factura, $this->id_producto, $this->cantidad]);
            $new  = $this->validate($this->id_factura, $this->id_producto);
            $db->closedCon();
            return [
                'message'=> 'detalle_factura created',
                'data' => $new  
            ];
            
        
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }



}
