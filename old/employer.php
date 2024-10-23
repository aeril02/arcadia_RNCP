<?php

include 'db_config.php';

// form_ajout food
if (isset($_POST['ajouter_nourriture'])) {
    $animal_id = $_POST['animal_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $nourriture = $_POST['nourriture'];
    $quantite = $_POST['quantite'];

    $sql = "INSERT INTO consommation_nourriture (animal_id, date, heure, nourriture, quantite) 
            VALUES (:animal_id, :date, :heure, :nourriture, :quantite)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'animal_id' => $animal_id,
        'date' => $date,
        'heure' => $heure,
        'nourriture' => $nourriture,
        'quantite' => $quantite
    ]);
}

// SQL animal
$sql = "SELECT animal_id, prenom FROM animal";
$animaux = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Consommation Nourriture</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
       <!--header_same for all -->
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
    
    <h1>Ajouter la Consommation de Nourriture pour un Animal</h1>
    <form method="post">
        <!-- not sure for label , pour for="animal_id" -->
        <label for="animal_id">Sélectionner un animal :</label>
        <select name="animal_id" id="animal_id">
            <?php foreach ($animaux as $animal) : ?>
            <option value="<?= $animal['animal_id'] ?>"><?= htmlspecialchars($animal['prenom']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="date">Date :</label>
        <input type="date" name="date" id="date" required><br>

        <label for="heure">Heure :</label>
        <input type="time" name="heure" id="heure" required><br>

        <label for="nourriture">Nourriture :</label>
        <input type="text" name="nourriture" id="nourriture" required><br>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" required><br>

        <button type="submit" name="ajouter_nourriture">Ajouter</button>
    </form>

    <!-- recuperation du formulaire formCom a dev -->
</body>
</html>
