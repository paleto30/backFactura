<?php


namespace App\Http\Controllers;

use App\Models\DetalleFacturaModel;

class DetalleFController
{




    /* 
            get all registers
    */

    public function getAllDetails()
    {
        try {

            $detalle = DetalleFacturaModel::getAlldetalles();
            echo json_encode($detalle);

        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }


    /* 
            get by id
    */
    public function getDetalleById()
    {
        try {
            $id = $_GET['id'];
            $detalle = DetalleFacturaModel::getOneById($id);
            echo json_encode($detalle);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    


    

    public function insertAFactueDetail(){
        try {
            
            $datos = json_decode(file_get_contents('php://input'),true);
            $detalle = new DetalleFacturaModel(...$datos);
            echo json_encode($detalle->save());

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    } 


}