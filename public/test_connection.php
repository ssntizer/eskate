<?php
$servername = "sql10.freesqldatabase.com";
$username = "sql10733194";
$password = "6747MCSCCX";
$dbname = "sql10733194";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión exitosa";
$conn->close();
?>
