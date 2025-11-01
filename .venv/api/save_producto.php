<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once("../include/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Capturar datos 
    $codigo = trim($_POST["codigo_producto"] ?? '');
    $nombre = trim($_POST["nombre_producto"] ?? '');
    $bodega = trim($_POST["bodega"] ?? '');
    $sucursal = trim($_POST["sucursal"] ?? '');
    $moneda = trim($_POST["moneda"] ?? '');
    $precio = trim($_POST["precio"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $materiales = $_POST["material"] ?? [];

    if ($codigo === '' || $nombre === '' || $bodega === '' || $sucursal === '' ||
        $moneda === '' || $precio === '') {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    if (!is_numeric($precio) || $precio <= 0) {
        echo json_encode(["status" => "error", "message" => "El precio debe ser un número positivo."]);
        exit;
    }

    try {
        $conn->beginTransaction();

        $sql = "INSERT INTO productos 
                (codigo, nombre, bodega, sucursal, moneda, precio, descripcion)
                VALUES (:codigo, :nombre, :bodega, :sucursal, :moneda, :precio, :descripcion)
                RETURNING id_producto";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':codigo'   => $codigo,
            ':nombre'   => $nombre,
            ':bodega'   => $bodega,
            ':sucursal' => $sucursal,
            ':moneda'   => $moneda,
            ':precio'   => $precio,
            ':descripcion' => $descripcion
        ]);

        $id_producto = $stmt->fetchColumn();

        // Insertar materiales
        if (!empty($materiales)) {
            $sqlMaterial = "INSERT INTO producto_material (id_producto, material)
                            VALUES (:id_producto, :material)";
            $stmtMaterial = $conn->prepare($sqlMaterial);

            foreach ($materiales as $mat) {
                $stmtMaterial->execute([
                    ':id_producto' => $id_producto,
                    ':material' => $mat
                ]);
            }
        }

        $conn->commit();
        echo json_encode(["status" => "success", "message" => "✅ Producto registrado correctamente."]);

    } catch (PDOException $e) {
    $conn->rollBack();

    // Verificar si el error fue por clave duplicada
    if ($e->getCode() == '23505') {
        echo json_encode([
            "status" => "error",
            "message" => "❌ El código del producto ya existe. Ingrese un código diferente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "⚠️ Error al guardar: " . $e->getMessage()
        ]);
    }
}

} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
