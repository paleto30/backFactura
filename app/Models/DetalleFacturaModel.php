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


}
