<?php
// Configuration de la connexion à la base de données
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

// Fonctions

// Fonction pour récupérer les animaux et leurs rapports vétérinaires avec le label de la race
function getAnimalsWithReports($pdo) {
    $sql = "SELECT a.animal_id, a.prenom, r.label AS race_label, rv.date AS rapport_date, rv.detail, e.description AS etat_description 
            FROM animal a
            LEFT JOIN race r ON a.race_id = r.race_id
            LEFT JOIN rapport_veterinaire rv ON a.animal_id = rv.animal_id
            LEFT JOIN etat e ON rv.etat_id = e.etat_id
            ORDER BY a.animal_id, rv.date DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les animaux et leur historique de consommation de nourriture
function getAnimalsWithFoodHistory($pdo) {
    $sql = "SELECT a.animal_id, a.prenom, r.label AS race_label, al.date_passage, al.nourriture, al.grammage_nourriture 
            FROM animal a
            LEFT JOIN race r ON a.race_id = r.race_id
            LEFT JOIN alimentation al ON a.animal_id = al.animal_id
            ORDER BY a.animal_id, al.date_passage DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer la liste des services
function getServices($pdo) {
    $sql = "SELECT * FROM service";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer la liste des animaux pour un menu déroulant
function getAnimalList($pdo) {
    $sql = "SELECT animal_id, prenom FROM animal";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction de validation pour les formulaires d'ajout de nourriture
function validateFoodForm($data) {
    $errors = [];

    if (empty($data['animal_id'])) {
        $errors[] = "L'animal doit être sélectionné.";
    }
    if (empty($data['date'])) {
        $errors[] = "La date est obligatoire.";
    }
    if (empty($data['nourriture'])) {
        $errors[] = "Le type de nourriture est obligatoire.";
    }
    if (empty($data['quantite']) || $data['quantite'] <= 0) {
        $errors[] = "La quantité doit être un nombre positif.";
    }

    return $errors;
}

// Fonction pour afficher les messages d'erreur ou de succès
function displayMessages($messages, $type = 'error') {
    if (!empty($messages)) {
        $class = $type === 'error' ? 'message-error' : 'message-success';
        echo "<div class='$class'><ul>";
        foreach ($messages as $message) {
            echo "<li>" . htmlspecialchars($message) . "</li>";
        }
        echo "</ul></div>";
    }
}

// Initialisation des messages
$errors = [];
$success = [];

// Traitement de l'ajout de nourriture
if (isset($_POST['ajouter_nourriture'])) {
    $errors = validateFoodForm($_POST);
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO alimentation (animal_id, date_passage, nourriture, grammage_nourriture) VALUES (:animal_id, :date_passage, :nourriture, :quantite)");
            $stmt->execute([
                'animal_id' => $_POST['animal_id'],
                'date_passage' => $_POST['date'],
                'nourriture' => $_POST['nourriture'],
                'quantite' => $_POST['quantite']
            ]);
            $success[] = "Consommation ajoutée avec succès.";
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'ajout de la consommation : " . htmlspecialchars($e->getMessage());
        }
    }
}

// Récupérer les données pour l'affichage
$animaux_data = getAnimalsWithReports($pdo);
$services = getServices($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services et de la Nourriture des Animaux</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <script src="../source/java/Header_Footer.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <ul id="header"></ul>
        </nav>
    </header>

    <?php displayMessages($errors, 'error'); ?>
    <?php displayMessages($success, 'success'); ?>

    <h1>Ajouter la Consommation de Nourriture pour un Animal</h1>
    <form method="post" action="">
        <fieldset>
            <legend>Ajouter la Consommation de Nourriture pour un Animal</legend>
            
            <label for="animal_id">Sélectionner un animal :</label>
            <select name="animal_id" id="animal_id" required>
                <?php foreach (getAnimalList($pdo) as $animal) : ?>
                    <option value="<?= $animal['animal_id'] ?>"><?= htmlspecialchars($animal['prenom']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="date">Date :</label>
            <input type="date" name="date" id="date" required><br>

            <label for="nourriture">Nourriture :</label>
            <input type="text" name="nourriture" id="nourriture" required><br>

            <label for="quantite">Quantité (grammes) :</label>
            <input type="number" name="quantite" id="quantite" required step="0.01" min="0"><br>

            <button type="submit" name="ajouter_nourriture">Ajouter</button>
        </fieldset>
    </form>

    <h1>Modifier les Services du Zoo</h1>
    <?php foreach ($services as $service): ?>
        <form method="post" class="service-form">
            <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">

            <label for="nom_service_<?= $service['service_id'] ?>">Nom du service :</label>
            <input type="text" name="nom_service" id="nom_service_<?= $service['service_id'] ?>" value="<?= htmlspecialchars($service['nom']) ?>" required><br>

            <label for="description_service_<?= $service['service_id'] ?>">Description :</label>
            <textarea name="description_service" id="description_service_<?= $service['service_id'] ?>" required><?= htmlspecialchars($service['description']) ?></textarea><br>

            <button type="submit" name="modifier_service">Modifier</button>
        </form>
    <?php endforeach; ?>

    <h1>Rapports et Consommation par Animal</h1>
    <?php foreach ($animaux_data as $animal_id => $animal): ?>
        <section class="animal-section">
            <h2><?php echo htmlspecialchars($animal['prenom']) . ' (' . htmlspecialchars($animal['race_label']) . ')'; ?></h2>
            
            <details class="rapport-details">
                <summary>Rapports Vétérinaires</summary>
                <?php if (!empty($animal['rapports'])): ?>
                    <ul>
                        <?php foreach ($animal['rapports'] as $rapport): ?>
                            <li>
                                <strong>Date :</strong> <?php echo htmlspecialchars($rapport['date']); ?><br>
                                <strong>État :</strong> <?php echo htmlspecialchars($rapport['etat']); ?><br>
                                <strong>Détail :</strong> <?php echo htmlspecialchars($rapport['detail']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Aucun rapport disponible.</p>
                <?php endif; ?>
            </details>

            <details class="nourriture-details">
                <summary>Historique de Consommation de Nourriture</summary>
                <?php if (!empty($animal['nourritures'])): ?>
                    <ul>
                        <?php foreach ($animal['nourritures'] as $nourriture): ?>
                            <li>
                                <strong>Date :</strong> <?php echo htmlspecialchars($nourriture['date_passage']); ?><br>
                                <strong>Nourriture :</strong> <?php echo htmlspecialchars($nourriture['nourriture']); ?><br>
                                <strong>Quantité :</strong> <?php echo htmlspecialchars($nourriture['grammage_nourriture']); ?> g
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Aucune consommation de nourriture enregistrée.</p>
                <?php endif; ?>
            </details>
        </section>
    <?php endforeach; ?>

    <footer>
        <nav>
            <ul id="footer"></ul>
        </nav>
    </footer>
</body>
</html>
