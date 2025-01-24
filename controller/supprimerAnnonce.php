<?php
    session_start();
    include("./database.php");

    $id = $_SESSION['connectedUser'];
    $dossierImages = './../style/';

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    function supprimerAnnonce($conn) {
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if (empty($id)) {
            header("Location: ./../../vue/404/error.php");
            exit();
        }

        $requete = "DELETE FROM annonce WHERE id = :id";
        $stmt = $conn->prepare($requete);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
        exit();
    }

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        supprimerAnnonce($conn);
    } else {
        header("Location: ./../../vue/404/error.php");
        exit();
    }
?>