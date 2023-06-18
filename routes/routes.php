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
    $router->post('add', 'App\Http\Controllers\ProductoController@insertProducto');
});




$router->get('/api/detalle/','App\Http\Controllers\DetalleFController@getAllDetails');
$router->get('/api/detalle/one', 'App\Http\Controllers\DetalleFController@getDetalleById');










$router->run();
?>