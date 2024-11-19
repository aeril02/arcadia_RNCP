<?php
include('../config/fetch_habitatByAnimal.php');
?>
<!-- Code HTML pour afficher les données -->
<main>
    <?php foreach ($habitats as $habitat): ?>
        <section class="principalHabitat">
            <aside class="sectionHabitat">
                <h2><?php echo htmlspecialchars($habitat['nom']); ?></h2>
                <figure>
                    <img src="data:image/webp;base64,<?php echo $habitat['photo']; ?>" alt="Image de <?php echo htmlspecialchars($habitat['nom']); ?>">
                    <figcaption><?php echo htmlspecialchars($habitat['description']); ?></figcaption>
                </figure>
                <aside class="boutonAnimaux">
                    <?php foreach ($habitat['animaux'] as $animal): ?>
                        <details class="boutonAnimal" <?php echo isset($animal['animal_id']) ? 'data-animal-id="' . htmlspecialchars($animal['animal_id']) . '"' : ''; ?>>
                            <summary><strong><?php echo htmlspecialchars($animal['race']); ?></strong></summary>
                            <p><strong> Prénom :<?php echo htmlspecialchars($animal['prenom']); ?></strong></p>
                            <p>État: <?php echo htmlspecialchars($animal['etat']); ?></p>
                            <?php if ($animal['image']): ?>
                                <img src="data:image/webp;base64,<?php echo $animal['image']; ?>" alt="Image de <?php echo htmlspecialchars($animal['prenom']); ?>">
                            <?php endif; ?>
                        </details>
                    <?php endforeach; ?>
                </aside>
            </aside>

            <section class="commentaireHabitat">
                <p><?php echo htmlspecialchars($habitat['commentaire_habitat']); ?></p>
            </section>
        </section>
    <?php endforeach; ?>
</main>
