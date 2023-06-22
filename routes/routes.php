<?php 

namespace Routes;


use Dotenv\Dotenv;

// router
$router = new \Bramus\Router\Router(); 
$dotenv = Dotenv::createImmutable('./config/env/'); // -> config de el enviroment
$dotenv->load();




// clientes ruoutes
$router->mount('/api/cliente',function() use($router) {
    $router->get('/','App\Http\Controllers\ClienteController@getAllClientes');
    $router->get('/one','App\Http\Controllers\ClienteController@getOneById');
    $router->post('/add','App\Http\Controllers\ClienteController@addOneCliente');
    $router->get('/load','App\Http\Controllers\ClienteController@getClienteByCedula');
});



// factura routes 
$router->mount('/api/factura',function() use($router) {
    $router->get('/','App\Http\Controllers\FacturaController@getAllBills');
    $router->post('/add', 'App\Http\Controllers\FacturaController@createNewBill');
    $router->get('/one', 'App\Http\Controllers\FacturaController@getOneBillByCode');

});


// producto routes
$router->mount('/api/producto', function() use($router){
    $router->get('/','App\Http\Controllers\ProductoController@getAllProductos');
    $router->get('/one','App\Http\Controllers\ProductoController@getProductById');
    $router->post('/add', 'App\Http\Controllers\ProductoController@insertProducto');
    $router->get('/load', 'App\Http\Controllers\ProductoController@loadProduct');
});



// factura detalle routes
$router->get('/api/detalle/','App\Http\Controllers\DetalleFController@getAllDetails');
$router->get('/api/detalle/one', 'App\Http\Controllers\DetalleFController@getDetalleById');
$router->post('/api/detalle/add','App\Http\Controllers\DetalleFController@insertAFactureDetail');



// crear una factura 
$router->post('/api/crearFactura/add', 'App\Http\Controllers\CrearFacturacionController@registrarFactura');



$router->run();
?>