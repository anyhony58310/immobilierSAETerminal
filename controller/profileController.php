<?php
    session_start();

    include './database.php';

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    if (!isset($_SESSION['connectedUser'])) {
        header('location: http://localhost/sitesae/vue/404/error.php');
        exit;
    }

    $id = $_SESSION['connectedUser'];
    infosProfiles($conn, $id);

    function infosProfiles($conn, $id) {
        $query = "
            SELECT 
                utilisateurs.id, 
                utilisateurs.nom, 
                utilisateurs.prenom, 
                utilisateurs.pseudo, 
                utilisateurs.mail, 
                utilisateurs.tel,
                COUNT(annonce.id) AS annonce_count
            FROM utilisateurs
            LEFT JOIN annonce ON annonce.idUtilisateur = utilisateurs.id
            WHERE utilisateurs.id = :id
            GROUP BY utilisateurs.id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            echo json_encode(['error' => 'Aucune donnée trouvée']);
            return;
        }

        $informations = [];
        foreach ($resultats as $row) {
            $informations[] = [
                'id' => $row['id'],
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'pseudo' => $row['pseudo'],
                'mail' => $row['mail'],
                'tel' => $row['tel'],
                'annonces' => $row['annonce_count']
            ];
        }

        $_SESSION['infosUser'] = $informations;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($informations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
?>