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

// Sélection des habitats
$sql = "SELECT * FROM habitat";
$statement = $pdo->prepare($sql);
$statement->execute();
$habitats = $statement->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour de l'état de l'animal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etat_id'])) {
    $etat_id = $_POST['etat_id'];
    $animal_id = $_POST['animal_id'];
    
    $sql_update_etat = "UPDATE animal SET etat_id = :etat_id WHERE animal_id = :animal_id";
    $stmt = $pdo->prepare($sql_update_etat);
    $stmt->bindParam(':etat_id', $etat_id);
    $stmt->bindParam(':animal_id', $animal_id);
    $stmt->execute();
}

// Mise à jour des commentaires sur l'habitat
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
    <link rel="stylesheet" href="../source/css/style.css">
    <script src="../source/java/Header_Footer.js" defer></script>
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
            <section class="habitat">
                <h2><?php echo $habitat['nom']; ?></h2>
                <p><?php echo $habitat['description']; ?></p>
                
                <!-- Formulaire de mise à jour des commentaires sur l'habitat -->
                <form action="" method="POST" >
                    <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">
                    <label for="commentaire_habitat">Commentaire sur l'état de l'habitat :</label>
                    <textarea name="commentaire_habitat" id="commentaire_habitat" required><?php echo $habitat['commentaire_habitat']; ?></textarea>
                    <button type="submit">Mettre à jour le commentaire</button>
                </form>
                
                <summary>Animaux dans cet habitat</summary>
                <details class ="animauxHabitat">
                    <?php
                    // Récupération des animaux par habitat avec leur état
                    $habitat_id = $habitat['habitat_id'];
                    $sql_animaux = "SELECT animal.*, etat.description AS etat_description 
                                    FROM animal 
                                    LEFT JOIN etat ON animal.etat_id = etat.etat_id 
                                    WHERE habitat_id = :habitat_id";
                    $statement_animaux = $pdo->prepare($sql_animaux);
                    $statement_animaux->bindParam(':habitat_id', $habitat_id);
                    $statement_animaux->execute();
                    $animaux = $statement_animaux->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($animaux as $animal): ?>
                        <p><strong><?php echo $animal['prenom']; ?></strong> (État actuel : <?php echo $animal['etat_description']; ?>)</p>

                        <!-- Formulaire de mise à jour de l'état de l'animal -->
                        <form action="" method="POST">
                            <input type="hidden" name="animal_id" value="<?php echo $animal['animal_id']; ?>">
                            <label for="etat_id_<?php echo $animal['animal_id']; ?>">Modifier l'état :</label>
                            <select id="etat_id_<?php echo $animal['animal_id']; ?>" name="etat_id" required>
                                <?php
                                // Récupération des états possibles depuis la table etat
                                $sql_etats = "SELECT * FROM etat";
                                $statement_etats = $pdo->prepare($sql_etats);
                                $statement_etats->execute();
                                $etats = $statement_etats->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($etats as $etat): ?>
                                    <option value="<?php echo $etat['etat_id']; ?>" <?php echo ($etat['etat_id'] == $animal['etat_id']) ? 'selected' : ''; ?>>
                                        <?php echo $etat['description']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Mettre à jour l'état</button>
                        </form>
                        
                        <!-- Historique de l'alimentation -->
                        <h4>Historique de l'alimentation de <?php echo $animal['prenom']; ?> :</h4>
                        <details>
                            <ul>
                                <?php
                                $animal_id = $animal['animal_id'];
                                $sql_alimentation = "SELECT * FROM alimentation WHERE animal_id = :animal_id ORDER BY date_passage DESC";
                                $statement_alimentation = $pdo->prepare($sql_alimentation);
                                $statement_alimentation->bindParam(':animal_id', $animal_id);
                                $statement_alimentation->execute();
                                $alimentation_records = $statement_alimentation->fetchAll(PDO::FETCH_ASSOC);
                                    
                                foreach ($alimentation_records as $record): ?>
                                    <li>
                                        Date : <?php echo $record['date_passage']; ?>, 
                                        Nourriture : <?php echo $record['nourriture']; ?>, 
                                        Quantité : <?php echo $record['grammage_nourriture']; ?>g
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
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