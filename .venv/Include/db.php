<?php
// ======================================================================
// Archivo: include/db.php
// Propósito: Centralizar la conexión a PostgreSQL para todo el proyecto
// ======================================================================


ini_set('display_errors', 1);
error_reporting(E_ALL);


$host = "localhost";        
$port = "5432";             
$dbname = "productos_db";   
$user = "whouser";         
$pass = "fqiewfiuuqwbe";    

try {
    // Crear la conexión usando PDO
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("SET NAMES 'UTF8'");

} catch (PDOException $e) {
    die("❌ Error al conectar a la base de datos: " . $e->getMessage());
}
?>
