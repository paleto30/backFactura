<?php 

namespace App\Http\Controllers;

use App\Models\ProductoModel;

class ProductoController {


    public function __construct()
    {   

    }

    
    /* 
            obtener todos los productos 
    */
    public function getAllProductos()
    {
        try {
            $products = ProductoModel::getAllProducts();
            echo json_encode($products);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }



    /* 
            buscar por id 
    */
    public function getProductById()
    {
        try {
            $id = $_GET['id'];
            $product = ProductoModel::getOneProductById($id);
            echo json_encode($product);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }


    /* 
            insertar producto 
    */
    public function insertProducto()
    {
        try {
            $datos = json_decode(file_get_contents('php://input'),true);
            $product = new ProductoModel(...$datos);
            echo json_encode($product->insertProduct());
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
    


    public function loadProduct()
    {
        try {
            
            $cod_producto = $_GET['cod_producto'];
            $product = ProductoModel::getProductoInDb($cod_producto);
            echo json_encode($product);

        } catch (\Throwable $th) {
            echo $th->getMessage();
        }


    }


















}