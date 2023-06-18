<?php

namespace  Config\Database;

use PDO;

class Conexion 
{

    private $host;
    private $dbname;
    private $user;
    private $password;
    private $pdo;

    public function __construct()
    {
        $this->host =  $_ENV['HOST']; // 'localhost'; //getenv('HOST');
        $this->dbname = $_ENV['DATABASE'];
        $this->user = $_ENV['USER'];
        $this->password = $_ENV['PASSWORD'];
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname='. $this->dbname;
            $this->pdo = new PDO($dsn,$this->user,$this->password,);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "okkk....." ;
        } catch (\PDOException $th) {
            die("error" . $th);
        }
    }

    
    /* 
            retornar la conexion 
    */
    public function connect(){
        try {
            return $this->pdo;
        } catch (\PDOException $th) {
            $error = [
                'message' => 'Error al retornar la conexion',
                'error' => $th->getMessage()
            ];
            return $error;
        }
    }


    /* 
            * funcion disconnect * 
            -> cerrar la conexion a la db
    */
    public function closedCon(){
        $this->pdo = null;
    }


}