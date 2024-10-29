<?php
//co_bdd ; changer pour le fichier dbconfig
// mettre user avec mdp 
$host = 'localhost'; 
$dbname = 'arcadia'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Message d'erreur en cas d'échec de connexion
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}
?>