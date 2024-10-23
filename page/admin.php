<?php
// tentative non concluant pour character speciaux- fais aussi dans bdd , last ch : voir pour html ( balise deja active mais a check si new html ¿bloque?)
// db_config.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arcadia"; 

// pourquoi include ne fonctionne pas ? 
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// check bug 19/09/2024_ inverse habitat-services? certain habitat se retrouve dans les services 


// check si possibilité de fusionner tout les commandes ( avantages :effet moins paquet  default : ( ) )
// Gestion user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $email = $_POST['email'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $role_id = $_POST['role_id'];
    $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT); // Chiffrer le mot de passe, maybe something better?

    $sql = "INSERT INTO utilisateur (username, password, nom, prenom, role_id) VALUES ('$email', '$password_hashed', '$nom', '$prenom', $role_id)";
    if ($conn->query($sql) === TRUE) {
        echo "Nouvel utilisateur créé avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// delete user
if (isset($_GET['delete_user'])) {
    $username = $_GET['delete_user'];
    $sql = "DELETE FROM utilisateur WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
        echo "Utilisateur supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// delete service
if (isset($_GET['delete_service'])) {
    $service_id = $_GET['delete_service'];
    $sql = "DELETE FROM service WHERE service_id = $service_id";
    if ($conn->query($sql) === TRUE) {
        echo "Service supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// delete habitat
if (isset($_GET['delete_habitat'])) {
    $habitat_id = $_GET['delete_habitat'];
    $sql = "DELETE FROM habitat WHERE habitat_id = $habitat_id";
    if ($conn->query($sql) === TRUE) {
        echo "Habitat supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// delete animal
if (isset($_GET['delete_animal'])) {
    $animal_id = $_GET['delete_animal'];
    $sql = "DELETE FROM animal WHERE animal_id = $animal_id";
    if ($conn->query($sql) === TRUE) {
        echo "Animal supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// service screen
$services = $conn->query("SELECT * FROM service");

// habitat screen
$habitats = $conn->query("SELECT * FROM habitat");

// animal screen
$sql = "SELECT animal.*, race.label AS race_nom, habitat.nom AS habitat_nom
        FROM animal
        JOIN race ON animal.race_id = race.race_id
        JOIN habitat ON animal.habitat_id = habitat.habitat_id";
$animals = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administrateur - Zoo Arcadia</title>
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
<section id="admin-services">
<!-- liste services -->
    <h2>Liste des services</h2>
    <table>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while($service = $services->fetch_assoc()): ?> <!-- penser a js pour utiliser le tableau-->
            <tr>
                <td><?= $service['nom'] ?></td>
                <td><?= $service['description'] ?></td>
                <td><a href="?delete_service=<?= $service['service_id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">Supprimer</a></td>
<!-- verifiez si accesibilité _ better ou better <bouton>? -->
            </tr>
        <?php endwhile; ?>
    </table>
</section>
<!--liste habitat-->
<section id="admin-habitats">
    <h2>Liste des habitats</h2>
    <table>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while($habitat = $habitats->fetch_assoc()): ?>
            <tr>
                <td><?= $habitat['nom'] ?></td>
                <td><?= $habitat['description'] ?></td>
                <td><?= $habitat['commentaire_habitat'] ?></td>
                <td><a href="?delete_habitat=<?= $habitat['habitat_id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet habitat ?')">Supprimer</a></td>
                 <!-- verifiez si accesibilité _ better this ou better <bouton>?  
            </tr>
        <?php endwhile; ?>
    </table>
</section>

< !-- affichage animal-->
<section id="admin-animals">
    <h2>Liste des animaux</h2>
    <table>
        <tr>
            <th>Nom</th>
            <th>Espèce</th>
            <th>Habitat</th>
            <th>état de santé</th>
            <th>Action</th>
        </tr>
        <?php while($animal = $animals->fetch_assoc()): ?>
    <tr>
        <td><?= $animal['prenom'] ?></td>
        <td><?= $animal['race_nom'] ?></td> <!-- recup label de race_par table animal-->
        <td><?= $animal['habitat_nom'] ?></td> <!-- recup nom de habitat_par table animal -->
        <td><?= $animal['etat'] ?></td>
        <td><a href="?delete_animal=<?= $animal['animal_id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet animal ?')">Supprimer</a></td>
<!-- verifiez si accesibilité _ better this ou better <bouton>?   -->
    </tr>
<?php endwhile; ?>

    </table>
</section>

</body>
</html>

<?php
$conn->close();
?>