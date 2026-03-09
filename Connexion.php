<?php
$user = "root";
$pass = "";
$dbName = "bdd_gestion_licence";

try { 
    $con = new \PDO("mysql:host=127.0.0.1;dbname=$dbName;charset=UTF8", $user, $pass); 
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) { 
    die("Erreur de connexion : " . $e->getMessage());
}
?>