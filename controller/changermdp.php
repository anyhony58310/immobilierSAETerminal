<?php
    session_start();
    include './database.php';
    
    $db = new DatabaseConnexion();
    $conn = $db->connect();

    $id = $_SESSION['connectedUser'];

    if (isset($_POST['mdp']) && isset($_POST['mdpDeux'])) {
        $mdp = $_POST['mdp'];
        $mdpDeux = $_POST['mdpDeux'];

        if ($mdp !== $mdpDeux) {
            echo json_encode(["success" => false, "message" => "Les mots de passe sont différents."]);
        } else {
            $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

            $requete = "UPDATE utilisateurs SET mdp = :mdp WHERE id = :id";
            $stmt = $conn->prepare($requete);
            
            $stmt->bindParam(':mdp', $mdpHash);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "redirect" => "http://localhost/sitesae/vue/profile/profile.php"]); 
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour du mot de passe."]);
            }
        }
    }
?>
