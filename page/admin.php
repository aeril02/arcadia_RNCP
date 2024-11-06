<?php
// admin.php
include('../config/db_config.php');

// Gestion des utilisateurs (ajout uniquement dans ce formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, username, password, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $email, $password, $role]);
}

// Sélection des habitats pour affichage
$sql = "SELECT * FROM habitat";
$statement = $pdo->prepare($sql);
$statement->execute();
$habitats = $statement->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les états possibles pour le menu déroulant
$sql_etats = "SELECT * FROM etat";
$statement_etats = $pdo->prepare($sql_etats);
$statement_etats->execute();
$etats = $statement_etats->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour de l'état et ajout du rapport vétérinaire pour l'animal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etat_id'], $_POST['date'], $_POST['detail'])) {
    $etat_id = $_POST['etat_id'];
    $animal_id = $_POST['animal_id'];
    $date = $_POST['date'];
    $detail = $_POST['detail'];
    
    // Mettre à jour l'état de l'animal
    $sql_update_etat = "UPDATE animal SET etat_id = :etat_id WHERE animal_id = :animal_id";
    $stmt = $pdo->prepare($sql_update_etat);
    $stmt->execute(['etat_id' => $etat_id, 'animal_id' => $animal_id]);

    // Ajouter un rapport vétérinaire
    $sql_add_report = "INSERT INTO rapport_veterinaire (animal_id, date, detail, etat_id) VALUES (:animal_id, :date, :detail, :etat_id)";
    $stmt_report = $pdo->prepare($sql_add_report);
    $stmt_report->execute(['animal_id' => $animal_id, 'date' => $date, 'detail' => $detail, 'etat_id' => $etat_id]);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration du Zoo</title>
</head>
<body>

<h1>Administration du Zoo</h1>

<h2>Gestion des Utilisateurs</h2>

<!-- Formulaire de création de compte pour employé et vétérinaire -->
<form action="admin.php" method="POST">
    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>
    
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="email">Email (nom d'utilisateur) :</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>
    
    <label for="role">Rôle :</label>
    <select id="role" name="role" required>
        <option value="2">Vétérinaire</option>
        <option value="3">Employé</option>
    </select>
    
    <button type="submit" name="add_user">Créer le compte</button>
</form>

<!-- Affichage des utilisateurs existants -->
<h3>Liste des Utilisateurs</h3>
<table>
    <tr>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Mot de passe</th>
    </tr>
    <?php
    // Requête pour récupérer les utilisateurs et afficher le mot de passe
    $stmt = $pdo->query("SELECT prenom, nom, username, password, role_id FROM utilisateur");
    while ($user = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>{$user['prenom']}</td>";
        echo "<td>{$user['nom']}</td>";
        echo "<td>{$user['username']}</td>";
        
        // Afficher le rôle sous forme de texte plutôt que d'ID
        if ($user['role_id'] == 1) {
            $role = 'Admin';
        } elseif ($user['role_id'] == 2) {
            $role = 'Vétérinaire';
        } else {
            $role = 'Employé';
        }
        echo "<td>$role</td>";

        // Afficher le mot de passe
        echo "<td>{$user['password']}</td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Gestion des Animaux et Rapports -->
<?php foreach ($habitats as $habitat): ?>
    <section class="habitat">
        <h2>Habitat : <?php echo $habitat['nom']; ?></h2>
        <p>Description : <?php echo $habitat['description']; ?></p>

        <details>
            <summary>Animaux dans cet habitat</summary>
            <?php
            $habitat_id = $habitat['habitat_id'];
            $sql_animaux = "SELECT animal.*, etat.description AS etat_description 
                            FROM animal 
                            LEFT JOIN etat ON animal.etat_id = etat.etat_id 
                            WHERE habitat_id = :habitat_id";
            $statement_animaux = $pdo->prepare($sql_animaux);
            $statement_animaux->execute(['habitat_id' => $habitat_id]);
            $animaux = $statement_animaux->fetchAll(PDO::FETCH_ASSOC);

            foreach ($animaux as $animal): ?>
                <div>
                    <p><strong><?php echo $animal['prenom']; ?></strong> - État : <?php echo $animal['etat_description']; ?></p>

                    <form action="admin.php" method="POST">
                        <input type="hidden" name="animal_id" value="<?php echo $animal['animal_id']; ?>">
                        
                        <label for="etat_id_<?php echo $animal['animal_id']; ?>">État :</label>
                        <select id="etat_id_<?php echo $animal['animal_id']; ?>" name="etat_id">
                            <?php foreach ($etats as $etat): ?>
                                <option value="<?php echo $etat['etat_id']; ?>" <?php echo ($etat['etat_id'] == $animal['etat_id']) ? 'selected' : ''; ?>>
                                    <?php echo $etat['description']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label for="date_<?php echo $animal['animal_id']; ?>">Date du rapport :</label>
                        <input type="date" id="date_<?php echo $animal['animal_id']; ?>" name="date" required>
                        
                        <label for="detail_<?php echo $animal['animal_id']; ?>">Détail :</label>
                        <textarea id="detail_<?php echo $animal['animal_id']; ?>" name="detail" required></textarea>
                        
                        <button type="submit">Ajouter le rapport</button>
                    </form>

                    <!-- Historique de l'alimentation -->
                    <h4>Historique de l'alimentation de <?php echo $animal['prenom']; ?> :</h4>
                    <details>
                        <summary>Voir l'historique</summary>
                        <ul>
                            <?php
                            $animal_id = $animal['animal_id'];
                            $sql_alimentation = "SELECT * FROM alimentation WHERE animal_id = :animal_id ORDER BY date_passage DESC";
                            $statement_alimentation = $pdo->prepare($sql_alimentation);
                            $statement_alimentation->execute(['animal_id' => $animal_id]);
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
                </div>
            <?php endforeach; ?>
        </details>
    </section>
<?php endforeach; ?>

</body>
</html>