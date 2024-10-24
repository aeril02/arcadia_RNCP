<?php 
  include "../config/db_config.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <script src="../source/java/PrimaryJava.js" defer></script>

</head>
<body>
    <header>
        <nav>
            <ul id="header"></ul>
        </nav>
    </header>
        
        
    <main>
        <h1 id="tittleIndex">Presentation du ZOO </h1>
        <section id ="presentationZooIndex">
            <?php include "../source/php/presentation_Zoo.php"; ?>
        </section>
    </main>

    <aside>
        <h2 id = "H2ComZooIndex">laisser un avis sur notre ZOO </h2>
        <div id="commentZooIndex">
            <p>  TXT TXT TXT </p>
        </div>
        <nav>
            <ul id ="commentairesIndex"></ul>
        </nav>
    </aside>
    
    <footer>
        <na>
            <ul id="footer"></ul>
        </na>
    </footer>

</body>
</html>