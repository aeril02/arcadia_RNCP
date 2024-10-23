<?php
//include 'db_config.php';
//soit en mongo sois en sql, a voir les meileurs celon le besoin .( a faire sur mongo i guess )
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Contact Page</title>
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
        <!-- non fini .  changer nom balise .  configurer l'action ! -->
        <section>
            <h2>Laissez-nous un message</h2>
            <form action="" method="POST"><!--???????????-->
                <label for="pseudo">Pseudo:</label><br>
                <input type="text" id="pseudo" name="pseudo" required><br><br>

                <label for="avis">Votre avis:</label><br>
                <textarea id="avis" name="avis" rows="4" cols="50" required></textarea><br><br>

                <label for="email">Votre email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <button type="submit">Envoyer</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html>
