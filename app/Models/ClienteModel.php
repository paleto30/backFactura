<?php

namespace App\Models;

use Config\Database\Conexion;
use Exception;
use PDO;

class ClienteModel 
{


    public function __construct(private $cedula, private $nombre, private $correo,private $direccion,private $telefono)
    {
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
    }
 
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
        throw new Exception("Propiedad invalida: " . $name);
    }

    // esta funcion me retorna los parametros del cliente
    public function toString()
    {
        return [
            'cedula'    => $this->cedula,
            'nombre'    => $this->nombre,
            'correo'    => $this->correo,
            'direccion' => $this->direccion,
            'telefono'  => $this->telefono 
        ];
    }


   public static function getAllClientes(){
        try {
            $db = new Conexion;
            $query = "SELECT * FROM clientes";
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


   public static function getClienteById($id){
        try {

            $db = new Conexion; 
            $query = "select * from clientes where id = ?";    
            $stamento = $db->connect()->prepare($query);  
            $stamento->execute([$id]);

            $res = $stamento->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            if (empty($res)) {
                return [
                    'message'=>'Not Exist' 
                ];
            }
            return ['data'=>$res];
        } catch (\Exception $th) {
            return $th->getMessage();
        }

   }

   /*  
            funcion privada para obtener el cliente por cedula
    */
   public function getIdByCedula($cedu){
        try {
            $db = new Conexion;
            // validamos que la cedula y el correo que envian no esten registrados
            $queryValidated = "SELECT * FROM clientes WHERE  cedula = ? ";
            $stament = $db->connect()->prepare($queryValidated); 
            $stament->execute([$cedu]);
            $clienteExiste = $stament->fetchAll(PDO::FETCH_ASSOC);
            $db->closedCon();
            return $clienteExiste;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
   }

    /* 
            agregar un cliente
    */
   public  function addCliente(){
        try {
            $db = new Conexion;
            $clienteExiste = $this->getIdByCedula($this->cedula);
            
            if (empty($clienteExiste)) {
                // si no existe lo creamos 
                $query = "INSERT INTO clientes (cedula,nombre,correo,direccion,telefono) VALUES ( ?, ?, ?, ?, ?)";  
                $stamento = $db->connect()->prepare($query);
                $stamento->execute([
                    $this->cedula, 
                    $this->nombre,
                    $this->correo, 
                    $this->direccion, 
                    $this->telefono
                ]);
                $new =  $this->getIdByCedula($this->cedula);
                $db->closedCon();
                return [
                    'message' => 'Create Succesfuly',
                    'data' => $new
                ];
            }

            return [
                'message' => 'Ya existe un usuario con ese numero de documento',
                'data' => $clienteExiste
            ];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
   }

   
}