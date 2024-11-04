<?php
// Inclure la configuration de la base de données
include('../config/db_config.php');

try {
    $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations des animaux et des habitats associés
    $stmt = $conn->prepare("
        SELECT habitat.habitat_id, habitat.nom AS habitat_nom, habitat.description AS habitat_description,
               animal.animal_id, animal.prenom, animal.etat, race.label AS race_label
        FROM habitat
        LEFT JOIN animal ON habitat.habitat_id = animal.habitat_id
        LEFT JOIN race ON animal.race_id = race.race_id
        ORDER BY habitat.habitat_id, animal.animal_id
    ");
    $stmt->execute();

    $habitats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $habitat_id = $row['habitat_id'];
        
        if (!isset($habitats[$habitat_id])) {
            $habitats[$habitat_id] = [
                'id' => $habitat_id,
                'nom' => $row['habitat_nom'],
                'description' => $row['habitat_description'],
                'animaux' => []
            ];
        }

        if ($row['animal_id']) {
            $habitats[$habitat_id]['animaux'][] = [
                'id' => $row['animal_id'],
                'prenom' => $row['prenom'],
                'etat' => $row['etat'],
                'race' => $row['race_label']
            ];
        }
    }

    // Récupérer les informations des services
    $stmt = $conn->prepare("SELECT * FROM service ORDER BY service_id");
    $stmt->execute();

    $services = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $services[] = [
            'id' => $row['service_id'],
            'nom' => $row['nom'],
            'description' => $row['description']
        ];
    }

    $conn = null;
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!-- Code HTML pour afficher les données des habitats et animaux -->
<main>
    <section>
        <h2>Habitats et Animaux</h2>
        <?php foreach ($habitats as $habitat): ?>
            <div class="habitat">
                <h3><?php echo htmlspecialchars($habitat['nom']); ?></h3>
                <p><?php echo htmlspecialchars($habitat['description']); ?></p>
                
                <!-- Boutons de modification et suppression pour chaque habitat -->
                <button onclick="editItem(<?php echo $habitat['id']; ?>, 'habitat')">Modifier</button>
                <button onclick="deleteItem(<?php echo $habitat['id']; ?>, 'habitat')">Supprimer</button>
                
                <!-- Boucle pour afficher les animaux associés à cet habitat -->
                <?php foreach ($habitat['animaux'] as $animal): ?>
                    <div class="animal">
                        <h4><?php echo htmlspecialchars($animal['prenom']); ?> (<?php echo htmlspecialchars($animal['race']); ?>)</h4>
                        <p>État : <?php echo htmlspecialchars($animal['etat']); ?></p>

                        <!-- Boutons de modification et suppression pour chaque animal -->
                        <button onclick="editItem(<?php echo $animal['id']; ?>, 'animaux')">Modifier</button>
                        <button onclick="deleteItem(<?php echo $animal['id']; ?>, 'animaux')">Supprimer</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Code HTML pour afficher les données des services -->
    <section>
        <h2>Services</h2>
        <?php foreach ($services as $service): ?>
            <div class="service">
                <h3><?php echo htmlspecialchars($service['nom']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                
                <!-- Boutons de modification et suppression pour chaque service -->
                <button onclick="editItem(<?php echo $service['id']; ?>, 'service')">Modifier</button>
                <button onclick="deleteItem(<?php echo $service['id']; ?>, 'service')">Supprimer</button>
            </div>
        <?php endforeach; ?>
    </section>
</main>