<?php
// co_bdd ; changer pour le fichier dbconfig
// mettre user avec mdp 
$host = 'localhost'; 
$dbname = 'arcadia'; 
$username = 'root'; 
$password = ''; 
try {
    // Con a la bdd 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

// faire les select ici a la place de la page ? 
?>