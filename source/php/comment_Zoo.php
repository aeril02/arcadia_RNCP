<?php
require '../vendor/autoload.php';

$mongo = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongo->Arcadia->commentairesZoo;

// Récupérer les commentaire selection (les 3 plus recent et valider)
$commentairesZoo = $collection->find( 
    ['valide' => true],
    [ 
        'sort' => ['date' => -1],
        'limit' => 3
    ]);

foreach ($commentairesZoo as $commentaire) {
    echo '<div id="commentZooIndex">';
    echo '<h3>' . $commentaire['nom'] . '</h3>';
    echo '<p>' . $commentaire['date'] . '</p>';
    echo '<p>' . $commentaire['texte'] . '</p>';
    echo '</div>';
}
?>