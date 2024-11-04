<?php
// Inclure la fetch_services.php
include('fetch_services.php');
?>
<!-- Code HTML pour afficher les données -->
<main>
    <?php foreach ($services as $item): ?>
        <section class="principal">
            <div class="service">
                <h2><?php echo htmlspecialchars($item['nom']); ?></h2>
                <p><?php echo htmlspecialchars($item['description']); ?></p>
            </div>
        </section>
    <?php endforeach; ?>
</main>