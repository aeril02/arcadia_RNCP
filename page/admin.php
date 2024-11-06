<?php
// admin.php
include('../config/db_config.php');

// Connexion à MongoDB
require '../vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->Arcadia->consultations;


// Gestion des utilisateurs (ajout uniquement dans ce formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, password, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $email, $password, $role]);
}

// Suppression d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE user_id = ?");
    $stmt->execute([$user_id]);
}

/// Récupérer les habitats
$sql_habitats = "SELECT * FROM habitat";
$statement_habitats = $pdo->prepare($sql_habitats);
$statement_habitats->execute();
$habitats = $statement_habitats->fetchAll(PDO::FETCH_ASSOC);

// Gestion des actions de habitats (ajout, mise à jour, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un habitat
    if (isset($_POST['add_habitat'])) {
        $habitat_name = $_POST['habitat_name'];
        $habitat_description = $_POST['habitat_description'];

        $stmt = $pdo->prepare("INSERT INTO habitat (nom, description) VALUES (?, ?)");
        $stmt->execute([$habitat_name, $habitat_description]);
    }

    // Mise à jour d'un habitat
    if (isset($_POST['update_habitat'])) {
        $habitat_id = $_POST['habitat_id'];
        $habitat_name = $_POST['habitat_name'];
        $habitat_description = $_POST['habitat_description'];

        $stmt = $pdo->prepare("UPDATE habitat SET nom = ?, description = ? WHERE habitat_id = ?");
        $stmt->execute([$habitat_name, $habitat_description, $habitat_id]);
    }

    // Suppression d'un habitat
    if (isset($_POST['delete_habitat'])) {
        $habitat_id = $_POST['habitat_id'];

        $stmt = $pdo->prepare("DELETE FROM habitat WHERE habitat_id = ?");
        $stmt->execute([$habitat_id]);
    }
}

// Récupérer la liste des services pour affichage
$sql_services = "SELECT * FROM service";
$statement_services = $pdo->prepare($sql_services);
$statement_services->execute();
$services = $statement_services->fetchAll(PDO::FETCH_ASSOC);

// Gestion des actions pour les services (ajout, mise à jour, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un service
    if (isset($_POST['add_service'])) {
        $service_name = $_POST['service_name'];
        $service_description = $_POST['service_description'];

        $stmt = $pdo->prepare("INSERT INTO service (nom, description) VALUES (?, ?)");
        $stmt->execute([$service_name, $service_description]);
    }

    // Mise à jour d'un service
    if (isset($_POST['update_service'])) {
        $service_id = $_POST['service_id'];
        $service_name = $_POST['service_name'];
        $service_description = $_POST['service_description'];

        $stmt = $pdo->prepare("UPDATE service SET nom = ?, description = ? WHERE service_id = ?");
        $stmt->execute([$service_name, $service_description, $service_id]);
    }

    // Suppression d'un service
    if (isset($_POST['delete_service'])) {
        $service_id = $_POST['service_id'];

        $stmt = $pdo->prepare("DELETE FROM service WHERE service_id = ?");
        $stmt->execute([$service_id]);
    }
}

// Récupérer les animaux
// Récupérer les animaux
$sql_animaux = "SELECT animal.animal_id, animal.prenom, animal.etat, animal.habitat_id, habitat.nom AS habitat_nom 
                FROM animal
                LEFT JOIN habitat ON animal.habitat_id = habitat.habitat_id";
$statement_animaux = $pdo->prepare($sql_animaux);
$statement_animaux->execute();
$animaux = $statement_animaux->fetchAll(PDO::FETCH_ASSOC);
$statement_animaux = $pdo->prepare($sql_animaux);
$statement_animaux->execute();
$animaux = $statement_animaux->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les habitats pour le menu déroulant
$sql_habitats = "SELECT * FROM habitat";
$statement_habitats = $pdo->prepare($sql_habitats);
$statement_habitats->execute();
$habitats = $statement_habitats->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les états pour le menu déroulant
$sql_etats = "SELECT * FROM etat";
$statement_etats = $pdo->prepare($sql_etats);
$statement_etats->execute();
$etats = $statement_etats->fetchAll(PDO::FETCH_ASSOC);

// Gestion de la mise à jour et suppression d'un animal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_animal'])) {
        // Mise à jour d'un animal
        $animal_id = $_POST['animal_id'];
        $prenom = $_POST['prenom'];
        $habitat_id = $_POST['habitat_id'];
        $etat_id = $_POST['etat_id'];
        
        $stmt = $pdo->prepare("UPDATE animal SET prenom = ?, habitat_id = ?, etat_id = ? WHERE animal_id = ?");
        $stmt->execute([$prenom, $habitat_id, $etat_id, $animal_id]);
    }

    if (isset($_POST['delete_animal'])) {
        // Suppression d'un animal
        $animal_id = $_POST['animal_id'];
        
        $stmt = $pdo->prepare("DELETE FROM animal WHERE animal_id = ?");
        $stmt->execute([$animal_id]);
    }
}


// Initialiser les valeurs de tri et de filtre pour chaque section
$sort_order_rapports = isset($_GET['sort_order_rapports']) ? $_GET['sort_order_rapports'] : 'DESC';
$selected_animal_rapports = $_GET['animal_id_rapports'] ?? null;
$sort_order_alimentation = isset($_GET['sort_order_alimentation']) ? $_GET['sort_order_alimentation'] : 'DESC';
$selected_animal_alimentation = $_GET['animal_id_alimentation'] ?? null;

// Récupérer la liste des animaux pour le filtre
$sql_animals = "SELECT animal_id, prenom FROM animal";
$statement_animals = $pdo->prepare($sql_animals);
$statement_animals->execute();
$animals = $statement_animals->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les états possibles pour l'affichage dans les rapports vétérinaires
$sql_etats = "SELECT * FROM etat";
$statement_etats = $pdo->prepare($sql_etats);
$statement_etats->execute();
$etats = $statement_etats->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration du Zoo</title>
</head>
<body>
    <header>
        <nav>
            <ul id="header"></ul>
        </nav>
    </header>

<h1>Administration du Zoo</h1>


<section class="gestion_utilisateurs">
<div>
    <h2>Gestion des Utilisateurs</h2>

    <!-- Formulaire de création de compte pour employé et vétérinaire -->
    <form action="admin.php" method="POST">
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
        
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Email :</label>
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

    <h3>Liste des Utilisateurs</h3>
    <table>
        <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT user_id, prenom, nom, email, role_id FROM utilisateur");
        while ($user = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>{$user['prenom']}</td>";
            echo "<td>{$user['nom']}</td>";
            echo "<td>{$user['email']}</td>";
            
            $role = ($user['role_id'] == 1) ? 'Admin' : (($user['role_id'] == 2) ? 'Vétérinaire' : 'Employé');
            echo "<td>$role</td>";
            
            // Bouton de suppression pour chaque utilisateur
            echo "<td>
                    <form method='POST' action='admin.php' style='display:inline;'>
                        <input type='hidden' name='user_id' value='{$user['user_id']}'>
                        <button type='submit' name='delete_user' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');\">Supprimer</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</section>

<section class="gestion_services">
<div>
    <h2>Gestion des Services</h2>

    <!-- Formulaire pour ajouter un nouveau service -->
    <form method="POST" action="admin.php">
        <h3>Ajouter un Service</h3>
        <label for="service_name">Nom du Service :</label>
        <input type="text" id="service_name" name="service_name" required>
        
        <label for="service_description">Description :</label>
        <input type="text" id="service_description" name="service_description" required>
        
        <button type="submit" name="add_service">Ajouter le Service</button>
    </form>

    <!-- Liste des services existants avec options de mise à jour et suppression -->
    <h3>Liste des Services</h3>
    <?php foreach ($services as $service): ?>
        <div>
            <form method="POST" action="admin.php">
                <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">

                <label for="service_name_<?php echo $service['service_id']; ?>">Nom :</label>
                <input type="text" id="service_name_<?php echo $service['service_id']; ?>" name="service_name" value="<?php echo htmlspecialchars($service['nom']); ?>" required>

                <label for="service_description_<?php echo $service['service_id']; ?>">Description :</label>
                <input type="text" id="service_description_<?php echo $service['service_id']; ?>" name="service_description" value="<?php echo htmlspecialchars($service['description']); ?>" required>

                <button type="submit" name="update_service">Mettre à jour</button>
                <button type="submit" name="delete_service" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">Supprimer</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</section>
<section class="gestion_habitats">
    <div>
        <h2>Gestion des Habitats</h2>

        <!-- Formulaire pour ajouter un nouvel habitat -->
        <form method="POST" action="admin.php" enctype="multipart/form-data">
            <h3>Ajouter un Habitat</h3>
            <label for="habitat_name">Nom de l'Habitat :</label>
            <input type="text" id="habitat_name" name="habitat_name" required>
            
            <label for="habitat_description">Description :</label>
            <input type="text" id="habitat_description" name="habitat_description" required>

            <label for="commentaire_habitat">Commentaire :</label>
            <input type="text" id="commentaire_habitat" name="commentaire_habitat" required>

            <label for="photo">Photo :</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>
            
            <button type="submit" name="add_habitat">Ajouter l'Habitat</button>
        </form>

        <!-- Liste des habitats existants avec options de mise à jour et suppression -->
        <h3>Liste des Habitats</h3>
        <?php foreach ($habitats as $habitat): ?>
            <div>
                <form method="POST" action="admin.php" enctype="multipart/form-data">
                    <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">

                    <label for="habitat_name_<?php echo $habitat['habitat_id']; ?>">Nom :</label>
                    <input type="text" id="habitat_name_<?php echo $habitat['habitat_id']; ?>" name="habitat_name" value="<?php echo htmlspecialchars($habitat['nom']); ?>" required>

                    <label for="habitat_description_<?php echo $habitat['habitat_id']; ?>">Description :</label>
                    <input type="text" id="habitat_description_<?php echo $habitat['habitat_id']; ?>" name="habitat_description" value="<?php echo htmlspecialchars($habitat['description']); ?>" required>

                    <label for="commentaire_habitat_<?php echo $habitat['habitat_id']; ?>">Commentaire :</label>
                    <input type="text" id="commentaire_habitat_<?php echo $habitat['habitat_id']; ?>" name="commentaire_habitat" value="<?php echo htmlspecialchars($habitat['commentaire_habitat']); ?>" required>

                    <label for="photo_<?php echo $habitat['habitat_id']; ?>">Photo :</label>
                    <input type="file" id="photo_<?php echo $habitat['habitat_id']; ?>" name="photo" accept="image/*">

                    <?php if ($habitat['photo']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($habitat['photo']); ?>" alt="Photo de l'Habitat" width="100">
                    <?php endif; ?>

                    <button type="submit" name="update_habitat">Mettre à jour</button>
                    <button type="submit" name="delete_habitat" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet habitat ?');">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<section class="gestion_animaux">
    <h2>Gestion des Animaux</h2>
    <h3>Liste des Animaux</h3>
    
    <?php foreach ($animals as $animal): ?>
        <div>
            <form method="POST" action="admin.php">
                <input type="hidden" name="animal_id" value="<?php echo $animal['animal_id']; ?>">

                <label for="prenom_<?php echo $animal['animal_id']; ?>">Nom :</label>
                <input type="text" id="prenom_<?php echo $animal['animal_id']; ?>" name="prenom" value="<?php echo htmlspecialchars($animal['prenom']); ?>" required>
                
                <label for="habitat_id_<?php echo $animal['animal_id']; ?>">Habitat :</label>
                <select id="habitat_id_<?php echo $animal['animal_id']; ?>" name="habitat_id">
                    <?php foreach ($habitats as $habitat): ?>
                        <option value="<?php echo $habitat['habitat_id']; ?>" 
                            <?php echo (isset($animal['habitat_id']) && $animal['habitat_id'] == $habitat['habitat_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($habitat['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="etat_id_<?php echo $animal['animal_id']; ?>">État :</label>
                <select id="etat_id_<?php echo $animal['animal_id']; ?>" name="etat_id" required>
                    <?php foreach ($etats as $etat): ?>
                        <option value="<?php echo $etat['etat_id']; ?>" 
                            <?php echo (isset($animal['etat_id']) && $animal['etat_id'] == $etat['etat_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($etat['description']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                                
                <button type="submit" name="update_animal">Mettre à jour</button>
                <button type="submit" name="delete_animal" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet animal ?');">Supprimer</button>
            </form>
        </div>
    <?php endforeach; ?>
</section>



<section class="gestion_alimentation">
<!-- Section Historique d'Alimentation -->
    <div>
        <h2>Historique de l'Alimentation</h2>
        
        <!-- Formulaire de tri et de filtre pour l'historique d'alimentation -->
        <form method="GET" action="admin.php">
            <label for="animal_id_alimentation">Filtrer par animal :</label>
            <select id="animal_id_alimentation" name="animal_id_alimentation">
                <option value="">Tous les animaux</option>
                <?php foreach ($animals as $animal): ?>
                    <option value="<?php echo $animal['animal_id']; ?>" <?php echo ($selected_animal_alimentation == $animal['animal_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($animal['prenom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="sort_order_alimentation">Trier par date :</label>
            <select id="sort_order_alimentation" name="sort_order_alimentation">
                <option value="DESC" <?php echo $sort_order_alimentation === 'DESC' ? 'selected' : ''; ?>>Date décroissante</option>
                <option value="ASC" <?php echo $sort_order_alimentation === 'ASC' ? 'selected' : ''; ?>>Date croissante</option>
            </select>
            
            <button type="submit">Appliquer le filtre et le tri</button>
        </form>

        <ul>
            <?php
            $sql_alimentation = "SELECT * FROM alimentation";
            if ($selected_animal_alimentation) {
                $sql_alimentation .= " WHERE animal_id = :animal_id";
            }
            $sql_alimentation .= " ORDER BY date_passage $sort_order_alimentation";
            $statement_alimentation = $pdo->prepare($sql_alimentation);

            if ($selected_animal_alimentation) {
                $statement_alimentation->execute(['animal_id' => $selected_animal_alimentation]);
            } else {
                $statement_alimentation->execute();
            }

            $alimentation_records = $statement_alimentation->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($alimentation_records as $record): ?>
                <li>
                    <strong>Date :</strong> <?php echo $record['date_passage']; ?>, 
                    <strong>Nourriture :</strong> <?php echo $record['nourriture']; ?>, 
                    <strong>Quantité :</strong> <?php echo $record['grammage_nourriture']; ?>g
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<section class="gestion_rapport_veterinaire">
<!-- Section Rapports Vétérinaires -->
    <div>
        <h2>Rapports Vétérinaires</h2>
        
        <!-- Formulaire de tri et de filtre pour les rapports vétérinaires -->
        <form method="GET" action="admin.php">
            <label for="animal_id_rapports">Filtrer par animal :</label>
            <select id="animal_id_rapports" name="animal_id_rapports">
                <option value="">Tous les animaux</option>
                <?php foreach ($animals as $animal): ?>
                    <option value="<?php echo $animal['animal_id']; ?>" <?php echo ($selected_animal_rapports == $animal['animal_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($animal['prenom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="sort_order_rapports">Trier par date :</label>
            <select id="sort_order_rapports" name="sort_order_rapports">
                <option value="DESC" <?php echo $sort_order_rapports === 'DESC' ? 'selected' : ''; ?>>Date décroissante</option>
                <option value="ASC" <?php echo $sort_order_rapports === 'ASC' ? 'selected' : ''; ?>>Date croissante</option>
            </select>
            
            <button type="submit">Appliquer le filtre et le tri</button>
        </form>

        <ul>
            <?php
            $sql_rapports = "SELECT * FROM rapport_veterinaire";
            if ($selected_animal_rapports) {
                $sql_rapports .= " WHERE animal_id = :animal_id";
            }
            $sql_rapports .= " ORDER BY date $sort_order_rapports";
            $statement_rapports = $pdo->prepare($sql_rapports);
            
            if ($selected_animal_rapports) {
                $statement_rapports->execute(['animal_id' => $selected_animal_rapports]);
            } else {
                $statement_rapports->execute();
            }

            $rapports = $statement_rapports->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($rapports as $rapport):
                $etat_description_array = array_filter($etats, fn($etat) => $etat['etat_id'] == $rapport['etat_id']);
                $etat_description = reset($etat_description_array)['description'] ?? 'N/A';
            ?>
                <li>
                    <strong>Date :</strong> <?php echo $rapport['date']; ?>, 
                    <strong>Détail :</strong> <?php echo htmlspecialchars($rapport['detail']); ?>, 
                    <strong>État :</strong> <?php echo $etat_description; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
    <footer>
        <na>
            <ul id="footer"></ul>
        </na>
    </footer>
</body>
</html>