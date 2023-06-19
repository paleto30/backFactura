

-- tabla de productos
CREATE TABLE productos(
    id int PRIMARY KEY AUTO_INCREMENT COMMENT "llave primaria del producto",
	cod_producto VARCHAR(8) NOT NULL UNIQUE COMMENT "codigo unico del producto",   
    nombre_producto VARCHAR(255) NOT NULL COMMENT "nombre correspondiente al producto",
    stock int(8) NOT NULL COMMENT "cantidad existente del producto",
    valor_unidad FLOAT NOT NULL COMMENT "valor de cada unidad del producto"
);

CREATE TABLE clientes(
    id int PRIMARY KEY AUTO_INCREMENT COMMENT "llave primaria de cliente",
	cedula VARCHAR(10) NOT NULL UNIQUE COMMENT "# documento unico",   
    nombre VARCHAR(255) NOT NULL COMMENT "nombre cliente",
    correo VARCHAR(255) NOT NULL COMMENT "correo cliente",
    direccion VARCHAR(255) NOT NULL COMMENT "domicilio cliente",
    telefono VARCHAR(15) NOT NULL COMMENT "# contacto"
);


CREATE TABLE facturas(
    id int PRIMARY KEY AUTO_INCREMENT COMMENT "llave primaria de factura",
	codigo_factura VARCHAR(10) NOT NULL UNIQUE COMMENT "codigo unico",   
    nombre_vendedor VARCHAR(255) NOT NULL COMMENT "nombre vendedor",
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "fecha creacion",
    id_cliente int NOT NULL COMMENT "fk del tabla cliente",
    CONSTRAINT fk1_facturas FOREIGN KEY (id_cliente) REFERENCES clientes(id)
);

CREATE TABLE detalle_factura(
    id int PRIMARY KEY AUTO_INCREMENT COMMENT "llave primaria de factura",
	id_factura INT NOT NULL COMMENT "id de factura",   
    id_producto INT NOT NULL COMMENT "id de producto",
    cantidad INT NOT NULL COMMENT "cantidad de productos",
    CONSTRAINT fk1_detalles_factura FOREIGN KEY (id_factura) REFERENCES facturas(id),
    CONSTRAINT fk2_detalles_factura FOREIGN KEY (id_producto) REFERENCES productos(id)
);




/*     -- consulta para obtener el id y nombre del cliente que tiene una determinada factura

        SELECT clientes.id, clientes.nombre,facturas.codigo_factura, facturas.fecha, SUM(detalle_factura.cantidad) as 'cantidad productos', 
        sum(productos.valor_unidad * detalle_factura.cantidad) as 'precio total' 
        FROM clientes, facturas,detalle_factura, productos 
        WHERE clientes.id = facturas.id_cliente 
        AND facturas.id = detalle_factura.id_factura 
        AND detalle_factura.id_producto = productos.id 
        AND facturas.codigo_factura = 'F0001';


        -- consulta para obtener los productos relacionados a una factura en concreto

        SELECT productos.cod_producto , productos.nombre_producto, detalle_factura.cantidad , productos.valor_unidad 
        from facturas, detalle_factura, productos 
        WHERE facturas.id = detalle_factura.id_factura 
        AND detalle_factura.id_producto = productos.id 
        AND facturas.codigo_factura = 'F0001';
*/