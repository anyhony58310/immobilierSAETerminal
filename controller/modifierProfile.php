<?php
    session_start();
    include './database.php';

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    if (isset($_SESSION['connectedUser'])) {
        $id = $_SESSION['connectedUser'];

        if (isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['email'])) {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $telephone = htmlspecialchars($_POST['telephone']);
            $email = htmlspecialchars($_POST['email']);

            $requete = "UPDATE utilisateurs 
                        SET pseudo = :pseudo, nom = :nom, prenom = :prenom, tel = :telephone, mail = :email
                        WHERE id = :id";

            try {
                $stmt = $conn->prepare($requete);

                $stmt->bindParam(':pseudo', $pseudo);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':telephone', $telephone);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':id', $id);

                if ($stmt->execute()) {
                    echo json_encode(["success" => true, "redirect" => "http://localhost/sitesae/vue/profile/profile.php"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour des informations."]);
                }
            } catch (Exception $e) {
                echo json_encode(["success" => false, "message" => "Une erreur est survenue : " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données incomplètes."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Utilisateur non connecté."]);
    }
?>
