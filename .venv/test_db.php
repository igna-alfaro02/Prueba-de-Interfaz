<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once("include/db.php");
try {
    if ($conn instanceof PDO) {
        echo "✅ Conexión exitosa a PostgreSQL (PDO).<br>";
        $stmt = $conn->query("SELECT version()");
        echo "Versión: " . $stmt->fetchColumn();
    } else {
        echo "❌ \$conn no está definido o no es PDO.";
    }
} catch (PDOException $e) {
    echo "❌ Error PDO: " . htmlspecialchars($e->getMessage());
}
?>
