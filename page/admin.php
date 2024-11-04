<?php
/*session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté et s'il a le rôle d'admin
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    // affiche un message d'erreur
    echo "Vous n'êtes pas autorisé à accéder à cette page.";
    exit(); // Arrête l'exécution du script
}*/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement d'utilisateur</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <script src="../source/java/Header_Footer.js" defer></script>
    <script src="../source/java/forAdmin/formBdd.js" defer></script>
</head>
<body>

    <header>
        <nav>
            <ul id="header"></ul>
        </nav>
    </header>

<section class="creationUser">
    <h2>Enregistrement d'un nouvel utilisateur</h2>
    <form action="../source/php/forAdmin/register_user.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br><br>

        <label for="role_id">Rôle :</label>
        <select id="role_id" name="role_id" required>
            <option value="2">Vétérinaire</option>
            <option value="3">Employé</option>
        </select><br><br>

        <button type="submit">inscrire l'utilisateur</button>
    </form>
</section>

<main>
    <section class="vueAll">
        <?php include ("../source/php/forAdmin/vue_item.php"); ?>
    </section>
</main>

    <!-- footer_same for all -->
    <footer>
        <na>
            <ul id="footer"></ul>
        </na>
    </footer>

</body>
</html>
