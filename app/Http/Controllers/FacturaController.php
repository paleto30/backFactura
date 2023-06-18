<?php

namespace App\Http\Controllers;

use App\Models\FacturaModel;


class FacturaController {



    /* 
            funcion que mee permite retornar
            los registros de facturas a la ruta
    */
    public function getAllBills()
    {
        try {
            $bills = FacturaModel::getAllBill();
            echo json_encode($bills);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }       
    }



    /* 
            funcion que recibe la data de la factura y crea una nueva  factura
    */
    public function createNewBill()
    {
        try {
            $datos = json_decode(file_get_contents('php://input'),true);
            $factura = new FacturaModel(...$datos);
            echo json_encode($factura->insertAbill());
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }



    /* 
            funcion para obtener una factura por su codigo 
    */
    public function getOneBillByCode()
    {
        try {
            $id = $_GET['id'];
            $factura = FacturaModel::getOneBillById($id);
            echo json_encode($factura);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

    }
    
    
}




