<?php
    session_start();
    include './database.php';
    
    $db = new DatabaseConnexion();
    $conn = $db->connect();

    $id = $_SESSION['connectedUser'];

    $dossierImages = './../style/';

    if (isset($_POST['supprimer']) && isset($_POST['supprimerDeux'])) {
        $supprimer = $_POST['supprimer'];
        $supprimerDeux = $_POST['supprimerDeux'];

        if (strtolower($supprimer) !== 'supprimer') {
            echo json_encode(["success" => false, "message" => "Veuillez s'il vous plaît entrer 'supprimer' ou 'SUPPRIMER'."]);
            exit;
        }

        if (strtolower($supprimerDeux) !== 'supprimer') {
            echo json_encode(["success" => false, "message" => "Veuillez s'il vous plaît entrer 'supprimer' ou 'SUPPRIMER'."]);
            exit;
        }

        if ($supprimer !== $supprimerDeux) {
            echo json_encode(["success" => false, "message" => "Les mots sont différents."]);
            exit;
        }

        $recupIdAnnonces = "SELECT id FROM annonce WHERE idUtilisateur = :id";
        $recup = $conn->prepare($recupIdAnnonces);
        $recup->bindParam(':id', $id);
        $recup->execute();

        $annonces = $recup->fetchAll(PDO::FETCH_ASSOC);

        foreach ($annonces as $annonce) {
            $idAnnonce = $annonce['id'];
            $recupCheminImage = "SELECT chemin FROM images WHERE idAnnonce = :idAnnonce";
            $cheminImage = $conn->prepare($recupCheminImage);
            $cheminImage->bindParam(':idAnnonce', $idAnnonce);
            $cheminImage->execute();

            $images = $cheminImage->fetchAll(PDO::FETCH_ASSOC);

            foreach ($images as $image) {
                $cheminASupprimer = $image['chemin'];

                $cheminComplet = $dossierImages . $cheminASupprimer;

                if (file_exists($cheminComplet)) {
                    unlink($cheminComplet);
                }
                else {
                    echo json_encode(["success" => false, "message" => "Erreur lors de la suppression du compte"]);
                    exit;
                }
            }

            $requeteSupprImages = "DELETE FROM images WHERE idAnnonce = :idAnnonce";
            $stmtSupprImages = $conn->prepare($requeteSupprImages);
            $stmtSupprImages->bindParam(':idAnnonce', $idAnnonce);
            $stmtSupprImages->execute();
        }

        $requeteAnnonce = "DELETE FROM annonce WHERE idUtilisateur = :id";
        $stmtAnnonce = $conn->prepare($requeteAnnonce);
        $stmtAnnonce->bindParam(':id', $id);

        if ($stmtAnnonce->execute()) {
            $requeteUtilisateur = "DELETE FROM utilisateurs WHERE id = :id";
            $stmtUtilisateur = $conn->prepare($requeteUtilisateur);
            $stmtUtilisateur->bindParam(':id', $id);

            if ($stmtUtilisateur->execute()) {
                session_unset();
                session_destroy();
                setcookie(session_name(), '', time() - 3600, '/');

                echo json_encode(["success" => true, "redirect" => "http://localhost/sitesae/index.php"]);
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de la tentative de désinscription"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la tentative de désinscription"]);
        }
    }
?>