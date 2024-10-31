<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <script src="../source/java/Header_Footer.js" defer></script>
    <script src="../source/java//forIndex/comment_Zoo.js" defer></script>
    <script src="../source/java/forIndex/ajout_comment_Zoo.js" defer></script>
<body>
<header>
        <nav>
            <ul id="header"></ul>
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
