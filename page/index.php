<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <script src="../source/java/Header_Footer.js" defer></script>
    <script src="../source/java//forIndex/comment_Zoo.js" defer></script>
    <script src="../source/java/forIndex/ajout_comment_Zoo.js" defer></script>
    
</head>
<body>
    <!--header_same for all -->
    <header>
        <nav>
            <ul id="header"></ul>
        </nav>
    </header>
        
       <!-- presentation du Zoo--> 
    <main>
        <h1 id="tittleIndex">Presentation du ZOO </h1>
        <section id ="presentationZooIndex">
            <?php include "../source/php/forIndex/presentation_Zoo.php"; ?>
        </section>
    </main>

    <aside>
        <!--formulaire pour ajouter des commentaire a mongodb -->
        <details id="detailsComZooIndex">
            <summary>laisser un avis sur notre ZOO</summary>
                <form id="ajoutComZooIndex">

                    <label id="labelComZooIndex" for="pseudoComZooIndex">Pseudo : </label>
                    <input type="text" name="pseudoComZooIndex" id="pseudoComZooIndex"  required><br>

                    <label id="labelComZooIndex" for="commentaireComZooIndex">Commentaire : </label>
                    <textarea name="commentaireComZooIndex" id="commentaireComZooIndex" required></textarea>
                
                    <input type="submit" id="submitComZooIndex" value="Envoyer votre commentaire">
                </form>
        </details>
        <!--liste des commentaires de mongodb -->
        <div id="commentZooIndex">
            <nav>
              <ul id ="commentairesIndex"></ul>
            </nav>
        </div>
    </aside>
    <!-- Conteneur de la pop-up vide -->
<div id="contactPopup" class="popup">
    <div class="popup-content">
        <span id="closePopup" class="close-btn">&times;</span>
        <div id="popupFormContent"></div> <!-- Contenu chargé dynamiquement ici -->
    </div>
</div>
    <!-- footer_same for all -->
    <footer>
        <na>
            <ul id="footer"></ul>
        </na>
    </footer>
</body>
</html>