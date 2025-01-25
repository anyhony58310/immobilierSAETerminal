<?php
    session_start();
    include './database.php';

    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['tel']) && isset($_POST['email']) && isset($_POST['mdp'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $pseudo = $_POST['pseudo'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $password = $_POST['mdp'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!preg_match('/^[0-9]{10}$/', $tel)) {
            echo json_encode(["success" => false, "message" => "Numéro de téléphone non valide."]);
            exit();
        }

        $db = new DatabaseConnexion();
        $conn = $db->connect();

        $checkUtilisateurs = "SELECT mail FROM utilisateurs WHERE mail = :email";
        $stmt = $conn->prepare($checkUtilisateurs);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $resultatCheck = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultatCheck) {
            echo json_encode(["success" => false, "message" => "Adresse mail déjà utilisée !"]);
            exit();
        }
        else {
            $ajoutUtilisateur = "INSERT INTO utilisateurs(nom, prenom, mail, tel, mdp, pseudo) VALUES(:nom, :prenom, :mail, :tel, :mdp, :pseudo)";
            $stmt = $conn->prepare($ajoutUtilisateur);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':mail', $email);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':mdp', $hashedPassword);
            $stmt->bindParam(':pseudo', $pseudo);

            if ($stmt->execute()) {
                $_SESSION['connexion'] = "Veuillez vous connecter";
                echo json_encode(["success" => true, "redirect" => "http://localhost/sitesae/vue/connexion/connexion.php"]);
                exit();
            }
            else {
                echo json_encode(["success" => false, "message" => "Un problème est survenue."]);
                exit();
            }
        }
    }
?>
