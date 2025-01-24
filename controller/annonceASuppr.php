<?php
    include './database.php';

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    if (isset($_POST['annonceASuppr'])) {
        $annonceASuppr = $_POST['annonceASuppr'];

        $supprimer = "DELETE FROM favories WHERE annonceid = :annonceid";
        $stmt = $conn->prepare($supprimer);
        $stmt->bindParam(':annonceid', $annonceASuppr);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        }
    }
?>