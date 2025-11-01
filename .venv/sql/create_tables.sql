
CREATE TABLE IF NOT EXISTS productos (
    id_producto SERIAL PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    bodega VARCHAR(100) NOT NULL,
    sucursal VARCHAR(100) NOT NULL,
    moneda VARCHAR(20) NOT NULL,
    precio NUMERIC(10,2) NOT NULL,
    descripcion TEXT,
    CONSTRAINT productos_codigo_unique UNIQUE (codigo)
);

CREATE TABLE IF NOT EXISTS producto_material (
    id_material SERIAL PRIMARY KEY,
    id_producto INT REFERENCES productos(id_producto) ON DELETE CASCADE,
    material VARCHAR(50) NOT NULL
);