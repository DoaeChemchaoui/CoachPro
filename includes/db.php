<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'plateforme_sportive', 3307);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>
