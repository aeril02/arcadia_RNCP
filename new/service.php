<?php
// Inclure la configuration de la base de données
include 'db_config.php';

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Requête pour récupérer les services
$sql = "SELECT nom, description FROM service";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services du Zoo Arcadia</title>
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

    <section id="services">
        <h2>Liste des services</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                // Afficher chaque service
                while($row = $result->fetch_assoc()) {
                    echo "<li><strong>" . $row['nom'] . "</strong><br>" . $row['description'] . "</li>";
                }
            } else {
                echo "Aucun service disponible.";
            }
            ?>
        </ul>
    </section>

    <footer>
        <p>© Zoo Arcadia 2024</p>
    </footer>

</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>