<?php
// Inclure la configuration de la base de données
include "db_config.php";

try {
    // Créer une connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialiser la variable $habitats comme un tableau vide
    $habitats = [];

    // Récupérer les informations des habitats et des animaux associés
    $stmt = $conn->prepare("
        SELECT 
            habitat.habitat_id, 
            habitat.nom AS habitat_nom, 
            habitat.description, 
            habitat.commentaire_habitat AS commentaire_habitat, 
            habitat.photo AS habitat_photo,
            animal.animal_id, 
            animal.prenom, 
            animal.etat_id, 
            animal.image AS animal_image, 
            race.label AS race_label, 
            etat.description AS etat_label
        FROM habitat
        LEFT JOIN animal ON habitat.habitat_id = animal.habitat_id
        LEFT JOIN race ON animal.race_id = race.race_id
        LEFT JOIN etat ON animal.etat_id = etat.etat_id
        ORDER BY habitat.habitat_id, animal.animal_id;
    ");

    $stmt->execute();

    // Parcourir les résultats pour structurer les données pour l'affichage
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $habitat_id = $row['habitat_id'];
        
        // Grouper les animaux par habitat
        if (!isset($habitats[$habitat_id])) {
            $habitats[$habitat_id] = [
                'nom' => $row['habitat_nom'],
                'description' => $row['description'],
                'commentaire_habitat' => $row['commentaire_habitat'],
                'photo' => base64_encode($row['habitat_photo']), // Convertir l'image en base64 pour affichage
                'animaux' => []
            ];
        }

        // Ajouter les informations de chaque animal dans l'habitat correspondant
        if ($row['animal_id']) {
            $habitats[$habitat_id]['animaux'][] = [
                'prenom' => $row['prenom'],
                'etat' => $row['etat_label'], // Nom de colonne correct
                'race' => $row['race_label'],
                'image' => base64_encode($row['animal_image']) // Convertir l'image en base64 pour affichage  
            ];
        }
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Fermer la connexion
$conn = null;
?>