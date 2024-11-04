<?php
// register_user.php

include('../config/db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collecte des données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role_id = $_POST['role_id'];

    // Hashage du mot de passe en utilisant SHA-1 pour obtenir une chaîne de 40 caractères
    $hashed_password = sha1($password);

    // Requête SQL pour insérer les données
    $sql = "INSERT INTO utilisateur (username, password, nom, prenom, role_id) VALUES (?, ?, ?, ?, ?)";

    // Préparer et exécuter la requête
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssi", $username, $hashed_password, $nom, $prenom, $role_id);
        if ($stmt->execute()) {
            echo "Utilisateur enregistré avec succès !";
        } else {
            echo "Erreur : " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur : " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
