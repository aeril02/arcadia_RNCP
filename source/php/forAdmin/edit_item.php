<?php
include('../../../config/db_config.php');

if (isset($_POST['id'], $_POST['type'], $_POST['nom'], $_POST['description'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définir la table cible en fonction du type
        $table = '';
        $idColumn = '';
        switch ($type) {
            case 'service':
                $table = 'service';
                $idColumn = 'service_id';
                break;
            case 'animaux':
                $table = 'animal';
                $idColumn = 'animal_id';
                break;
            case 'habitat':
                $table = 'habitat';
                $idColumn = 'habitat_id';
                break;
            default:
                echo json_encode(['success' => false, 'error' => 'Type non valide']);
                exit();
        }

        // Exécuter la requête de mise à jour
        $stmt = $conn->prepare("UPDATE $table SET nom = :nom, description = :description WHERE $idColumn = :id");
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
