<?php

include 'db_config.php';

// requete SQL, recup données base 
$query = "
    SELECT h.nom AS habitat_nom, h.description AS habitat_description, GROUP_CONCAT(a.prenom SEPARATOR ', ') AS animaux
    FROM habitat h
    LEFT JOIN animal a ON h.habitat_id = a.habitat_id
    GROUP BY h.habitat_id
";
// execute requete , secure 
try {
    $stmt = $pdo->query($query);
    $habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("pas de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Arcadia - Page d'accueil</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
 <? include 'header.php'; ?>

      <!-- Texte Zoo -->
    <main id="main_presentation">
        <div class="presentation">
            <h2>Présentation du Zoo</h2>
            <p>Arcadia est un zoo situé en France près de la forêt de Brocéliande, en bretagne depuis 1960. 
            Ils possèdent tout un panel d’animaux, réparti par habitat (savane, jungle, marais) et font 
            extrêmement attention à leurs santés. </p>
        </div>

    <!-- aside habitat/animaux  -->
    <aside id="infos_completes">
        <h2>Découvrez nos habitats et nos animaux</h2>

        <section>
            <h3>Nos Habitats</h3>
            <p>Le Zoo Arcadia offre des habitats adaptés à chaque espèce animale :</p>
            <ul>
                <?php foreach ($habitats as $habitat): ?>
                    <li>
                        <h4><?= htmlspecialchars($habitat['habitat_nom']) ?></h4>
                        <p><?= htmlspecialchars($habitat['habitat_description']) ?></p>
                        <details>
                            <summary>Voir les animaux de cet habitat</summary>
                            <p><?= htmlspecialchars($habitat['animaux']) ?></p>
                        </details>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </aside>

    </main>

    <!-- Avis visiteurs -->
    <aside>
        <h2>Laissez un commentaire</h2>
        <button id="FormCom">Laisser un commentaire</button>

        <!-- Formulaire caché initialement -->
        <div id="commentForm" style="display: none;">
            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" required>
            <br>
            <label for="avis">Votre avis :</label>
            <textarea id="avis" name="avis" required></textarea>
            <br>
            <button id="submitComment">Soumettre</button>
        </div>

        <!-- valid commentaire-->
        <div id="validatedComments">
            <h3>Commentaires:</h3>
        </div>
    </aside>
    <!-- verifier mongodb(lien avec employer-->
    <!-- footer_same for all  -->
    <footer>
        <ul>
            <li>&copy; 2024 Zoo Arcadia - Tous droits réservés</li>
            <li><a href="legal.php">Mentions légales</a></li>
            <li><a href="billetterie.php">Billetterie</a></li>
            <li><a href="reglement.php">Règlement intérieur</a></li>
            <li><a href="recrutement.php">Recrutement</a></li>
        </ul>
    </footer>
</body>
</html>