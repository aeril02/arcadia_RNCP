<?php
// Inclure le fichier de configuration PDO
require "db_config.php";

// Connexion à MongoDB
require '../vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->Arcadia->consultations;

// Requête MySQL pour récupérer les animaux avec PDO
try {
    $stmt = $pdo->query("SELECT animal_id, prenom FROM animal");
} catch (PDOException $e) {
    die("Erreur lors de la récupération des animaux : " . $e->getMessage());
}

// Parcourir chaque animal et créer un document dans MongoDB
while ($animal = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $collection->updateOne(
        ['animal_id' => $animal['animal_id']], // Filtre pour vérifier si l'animal existe déjà
        ['$setOnInsert' => [
            'animal_id' => $animal['animal_id'],
            'prenom' => $animal['prenom'],
            'consultations' => 0
        ]],
        ['upsert' => true] // Si l'animal n'existe pas, il est créé
    );
}

echo "Initialisation des documents MongoDB pour chaque animal terminée.";
?>
