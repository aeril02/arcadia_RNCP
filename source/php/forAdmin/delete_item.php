<?php
include('../../../config/db_config.php');

if (isset($_POST['id']) && isset($_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définir la table cible en fonction du type
        $table = '';
        switch ($type) {
            case 'service':
                $table = 'service';
                break;
            case 'animaux':
                $table = 'animal';
                break;
            case 'habitat':
                $table = 'habitat';
                break;
            default:
                echo json_encode(['success' => false, 'error' => 'Type non valide']);
                exit();
        }

        // Exécuter la requête de suppression
        $stmt = $conn->prepare("DELETE FROM $table WHERE {$type}_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
