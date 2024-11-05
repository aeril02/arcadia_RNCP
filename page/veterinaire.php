<?php
// Connexion à la base de données
$host = 'localhost'; 
$dbname = 'arcadia';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// select habitat
$sql = "SELECT * FROM habitat";
$statement = $pdo->prepare($sql);
$statement->execute();
$habitats = $statement->fetchAll(PDO::FETCH_ASSOC);

// requete maj animal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etat'])) {
    $etat = $_POST['etat'];
    $animal_id = $_POST['animal_id'];
    
    $sql_update_etat = "UPDATE animal SET etat = :etat WHERE animal_id = :animal_id";
    $stmt = $pdo->prepare($sql_update_etat);
    $stmt->bindParam(':etat', $etat);
    $stmt->bindParam(':animal_id', $animal_id);
    $stmt->execute();
}

// requete_maj habitat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire_habitat'])) {
    $commentaire = $_POST['commentaire_habitat'];
    $habitat_id = $_POST['habitat_id'];
    
    $sql_update_commentaire = "UPDATE habitat SET commentaire_habitat = :commentaire WHERE habitat_id = :habitat_id";
    $stmt = $pdo->prepare($sql_update_commentaire);
    $stmt->bindParam(':commentaire', $commentaire);
    $stmt->bindParam(':habitat_id', $habitat_id);
    $stmt->execute();
}
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
    <!--header_same for all -->
    <header>
        <nav>
            <ul id="header"></ul>
        </nav>
    </header>
    
    <main>
        <h1>Les habitats du zoo</h1>
        <?php foreach ($habitats as $habitat): ?>
            <section>
                <h2><?php echo $habitat['nom']; ?></h2>
                <p><?php echo $habitat['description']; ?></p>
                
                <!-- Form_maj_com -->
                <form action="" method="POST">
                    <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">
                    <label for="commentaire_habitat">Commentaire sur l'état de l'habitat :</label>
                    <textarea name="commentaire_habitat" id="commentaire_habitat" required><?php echo $habitat['commentaire_habitat']; ?></textarea>
                    <button type="submit">Mettre à jour le commentaire</button>
                </form>
                
                <summary>Animaux dans cet habitat</summary>
                <details>
                    <?php
                    // recup animal par habitat
                    $habitat_id = $habitat['habitat_id'];
                    $sql_animaux = "SELECT * FROM animal WHERE habitat_id = :habitat_id";
                    $statement_animaux = $pdo->prepare($sql_animaux);
                    $statement_animaux->bindParam(':habitat_id', $habitat_id);
                    $statement_animaux->execute();
                    $animaux = $statement_animaux->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($animaux as $animal): ?>
                        <p><strong><?php echo $animal['prenom']; ?></strong> (État actuel : <?php echo $animal['etat']; ?>)</p>

                        <!-- Form_maj animal -->
                        <form action="" method="POST">
                            <input type="hidden" name="animal_id" value="<?php echo $animal['animal_id']; ?>">
                            <label for="etat_<?php echo $animal['animal_id']; ?>">Modifier l'état :</label>
                            <input type="text" id="etat_<?php echo $animal['animal_id']; ?>" name="etat" value="<?php echo $animal['etat']; ?>" required>
                            <button type="submit">Mettre à jour l'état</button>
                        </form>
                    <?php endforeach; ?>
                </details>
            </section>
        <?php endforeach; ?>
    </main>

    <!-- footer_same for all -->
    <footer>
        <na>
            <ul id="footer"></ul>
        </na>
    </footer>
</body>
</html>
