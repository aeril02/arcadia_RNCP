<?php

include 'db_config.php';

// SQL habitat
$sql = "SELECT * FROM habitat";
$statement = $pdo->prepare($sql);
$statement->execute();
$habitats = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitats du Zoo Arcadia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
 
    <header>
        <nav>
            <ul>
                <li><img src="photo/logo_arcadia.jpg" alt="logo arcadia"></li>
                <li><a href="index.php">acceuil</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="habitats.php">Habitats</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Connexion</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <h1>Les habitats du zoo</h1>
        <?php foreach ($habitats as $habitat): ?>
            <section>
                <h2><?php echo $habitat['nom']; ?></h2>
                <p><?php echo $habitat['description']; ?></p>
                <summary>Animaux dans cet habitat</summary>
                <details>
                    <?php
                    // sql_animal _ de habitat 
                    $habitat_id = $habitat['habitat_id'];
                    $sql_animaux = "SELECT * FROM animal WHERE habitat_id = :habitat_id";
                    $statement_animaux = $pdo->prepare($sql_animaux);
                    $statement_animaux->bindParam(':habitat_id', $habitat_id);
                    $statement_animaux->execute();
                    $animaux = $statement_animaux->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($animaux as $animal): ?>
                        <p><strong><?php echo $animal['prenom']; ?></strong> (État : <?php echo $animal['etat']; ?>)</p>
                    <?php endforeach; ?>
                </details>
            </section>
        <?php endforeach; ?>
    </main>

    <footer>
        <ul>
            <li>&copy; 2024 Zoo Arcadia - Tous droits réservés</li>
            <li><a href="legal.html">Mentions légales</a></li>
            <li><a href="billeterie.html">Billetterie</a></li>
            <li><a href="reglement.html">Règlement intérieur</a></li>
            <li><a href="recrutement.html">Recrutement</a></li>
        </ul>   
    </footer>
</body>
</html>
