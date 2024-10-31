<?php
// Vérification de l'envoi du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Charger l'autoload MongoDB
    require '../../../vendor/autoload.php';

    if (class_exists('MongoDB\Client')) {
        echo "<p>MongoDB\Client is available.</p>";
    } else {
        echo "<p>MongoDB\Client is NOT available.</p>";
    }
    

    // Connexion à MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->zooArcadia->contactForms;

    // Récupérer les données du formulaire
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $avis = htmlspecialchars(trim($_POST['avis']));
    $email = htmlspecialchars(trim($_POST['email']));

    // Validation des champs pour éviter les injections malveillantes
    // Vérification de champs vides
    if (empty($pseudo) || empty($avis) || empty($email)) {
        echo "<p>Erreur : Tous les champs sont obligatoires.</p>";
        exit;
    }

    // Validation des champs pour éviter les injections malveillantes
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $pseudo)) {
        echo "<p>Erreur : Pseudo invalide.</p>";
        exit;
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo "<p>Erreur : Email invalide.</p>";
        exit;
    }

    $date_creation = new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp() * 1000);

    // Insérer les données dans MongoDB
    $collection->insertOne([
        'pseudo' => $pseudo,
        'avis' => $avis,
        'email' => $email,
        'date_creation' => $date_creation
    ]);

    echo "<p>Merci pour votre message ! Nous l'avons bien reçu.</p>";
}
?>
