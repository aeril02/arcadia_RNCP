<?php
// Inclure la configuration de la base de données
include "db_config.php";

try {
    // Créer une connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations des services
    $stmt = $conn->prepare("
        SELECT *
        FROM service
        ORDER BY service_id
    ");
    $stmt->execute();

    // Initialiser les données pour l'affichage
    $services = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $service_id = $row['service_id'];
        
        // Ajouter les informations du service
        $services[$service_id] = [
            'nom' => $row['nom'],
            'description' => $row['description'],
        ];
    }

    $conn = null;
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}