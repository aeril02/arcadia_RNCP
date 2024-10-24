const url = "../source/php/comment_Zoo.php"; // Chemin correct vers le fichier PHP

fetch(url)  // Faire une requête HTTP pour recharger les commentaires
    .then(response => response.json())  // tester si la requete est correct
    .then(data => {
        const commentDiv = document.getElementById("commentairesIndex");
        data.forEach(item => {
            const commentBlock = document.createElement("li");

            // Créer les éléments pour chaque champ du commentaire
            const name = document.createElement("h3");
            name.textContent = item.nom;

            const date = document.createElement("p");
            const optionDate = { year: 'numeric', month: 'long', day: 'numeric' };
            date.textContent = new Date(item.date).toLocaleDateString('fr-FR', optionDate); // Afficher la date

            const texte = document.createElement("p");
            texte.textContent = item.texte;

            // Ajouter les éléments au div commentBlock
            commentBlock.appendChild(name);
            commentBlock.appendChild(date);
            commentBlock.appendChild(texte);

            // Ajouter le div commentBlock à l'élément "comment"
            commentDiv.appendChild(commentBlock);
        });
    })
    .catch(error => {
        console.error('Erreur lors de la récupération des commentaires :', error);
    });
