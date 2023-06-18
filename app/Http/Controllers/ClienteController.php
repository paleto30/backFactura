<?php 

namespace App\Http\Controllers;

use App\Models\ClienteModel;


class ClienteController 
{

    public function __construct()
    {
        
    }


    /*  
            funcion para obtener todos los registros
            de la tabla clientes
    */
    public function getAllClientes()
    {   
        try {
            $clientes = ClienteModel::getAllClientes();
            echo json_encode($clientes);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        
    }


    /* 
        funcion para buscar los registros por id;
    */
    public function getOneById()    
    {
        try {
            $id = $_GET['id'];
            $cliente = ClienteModel::getClienteById($id);
            echo json_encode($cliente);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        
    }

    /* 
            funcion para insertar un registro
            en la tabla clientes
    */
    public function addOneCliente(){
        try {
            $datos = json_decode(file_get_contents('php://input'),true);
            $cliente = new ClienteModel(...$datos);  
            echo json_encode($cliente->addCliente());
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }   

    

}


?>