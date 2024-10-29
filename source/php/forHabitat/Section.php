<?php
// Inclure la configuration de la base de données
include('../config/db_config.php');

try {
    // Créer une connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations des habitats et des animaux associés
    $stmt = $conn->prepare("
        SELECT habitat.habitat_id, habitat.nom AS habitat_nom, habitat.description, habitat.commentaire_habitat,habitat.photo AS photo_habitat,
               animal.animal_id, animal.prenom, animal.etat, animal.image
        FROM habitat
        LEFT JOIN animal ON habitat.habitat_id = animal.habitat_id
        ORDER BY habitat.habitat_id, animal.animal_id
    ");
    $stmt->execute();

    // Initialiser les données pour l'affichage
    $habitats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $habitat_id = $row['habitat_id'];
        
        // Grouper les animaux par habitat
        if (!isset($habitats[$habitat_id])) {
            $habitats[$habitat_id] = [
                'nom' => $row['habitat_nom'],
                'description' => $row['description'],
                'commentaire_habitat' => $row['commentaire_habitat'],
                'photo' => base64_encode($row['photo_habitat']), // Convertir l'image en base64 pour affichage
                'animaux' => []
            ];
        }

        // Ajouter les informations de chaque animal dans l'habitat correspondant
        if ($row['animal_id']) {
            $habitats[$habitat_id]['animaux'][] = [
                'prenom' => $row['prenom'],
                'etat' => $row['etat'],
                'image' => base64_encode($row['image']) // Convertir l'image en base64 pour affichage
            ];
        }
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$conn = null;
?>
<!-- Code HTML pour afficher les données -->
<main>
    <?php foreach ($habitats as $habitat): ?>
        <section class="principal">
            <aside class="photoHabitat">
                <h2><?php echo htmlspecialchars($habitat['nom']); ?></h2>
                <figure>
                        <img src="data:image/webp;base64,<?php echo $habitat['photo']; ?>" alt="Image de <?php echo htmlspecialchars($habitat['nom']); ?>">
                        <figcaption><?php echo htmlspecialchars($habitat['description']); ?></figcaption>
                </figure>
                <section class="boutonAnimaux">
                    <?php foreach ($habitat['animaux'] as $animal): ?>
                        <div class="animal">
                            <p><strong><?php echo htmlspecialchars($animal['prenom']); ?></strong></p>
                            <p>État: <?php echo htmlspecialchars($animal['etat']); ?></p>
                            <?php if ($animal['image']): ?>
                                <img src="data:image/webp;base64,<?php echo $animal['image']; ?>" alt="Image de <?php echo htmlspecialchars($animal['prenom']); ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </section>
            </aside>

            <section class="commentaireHabitat">
                <p><?php echo htmlspecialchars($habitat['commentaire_habitat']); ?></p>
            </section>
        </section>
    <?php endforeach; ?>
</main>