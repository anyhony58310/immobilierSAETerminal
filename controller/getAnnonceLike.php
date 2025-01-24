<?php
    session_start();
    include("./database.php");

    $id = $_SESSION['connectedUser'];

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    function annoncesSansFiltres($conn, $id) {
        $query = "SELECT a.*, i.chemin 
        FROM annonce a
        LEFT JOIN images i ON a.id = i.idAnnonce
        INNER JOIN favories f ON a.id = f.annonceid
        WHERE f.utilisateurid = :userid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userid', $id);
        $stmt->execute();

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $annonces = [];
        foreach ($resultats as $row) {
            $idAnnonce = $row['id'];
            if (!isset($annonces[$idAnnonce])) {
                $annonces[$idAnnonce] = [
                    'id' => $row['id'],
                    'titre' => $row['titre'],
                    'prix' => $row['prix'],
                    'ville' => $row['ville'],
                    'surface' => $row['surface'],
                    'nombrePiece' => $row['nombrePiece'],
                    'type' => $row['type'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'images' => []
                ];
            }

            if ($row['chemin']) {
                $annonces[$idAnnonce]['images'][] = $row['chemin'];
            }
        }

        $annonces = array_values($annonces);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($annonces, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    function annoncesAvecFiltres($conn, $id) {
        $ville = isset($_GET['ville']) ? $_GET['ville'] : '';
        $option = isset($_GET['option']) ? $_GET['option'] : null;
        
        if ($option === "all") {
            $query = "SELECT a.*, i.chemin, u.nom AS utilisateur_nom, u.prenom AS utilisateur_prenom, u.pseudo AS utilisateur_pseudo, u.mail AS utilisateur_mail, u.tel AS utilisateur_tel 
                      FROM annonce a 
                      LEFT JOIN images i ON a.id = i.idAnnonce 
                      LEFT JOIN utilisateurs u ON a.idUtilisateur = u.id 
                      INNER JOIN favories f ON a.id = f.annonceid 
                      WHERE f.utilisateurid = :utilisateurid 
                      AND (:ville = '' OR a.ville = :ville) 
                      AND a.type IN (0, 1)";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':utilisateurid', $id);
            $stmt->bindParam(':ville', $ville);
            $stmt->execute();
        }

        else {
            $query = "SELECT a.*, i.chemin, u.nom AS utilisateur_nom, u.prenom AS utilisateur_prenom, u.pseudo AS utilisateur_pseudo, u.mail AS utilisateur_mail, u.tel AS utilisateur_tel 
                      FROM annonce a 
                      LEFT JOIN images i ON a.id = i.idAnnonce 
                      LEFT JOIN utilisateurs u ON a.idUtilisateur = u.id 
                      INNER JOIN favories f ON a.id = f.annonceid 
                      WHERE f.utilisateurid = :utilisateurid 
                      AND (:ville = '' OR a.ville = :ville) 
                      AND a.type = :typeOption";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':utilisateurid', $id);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':typeOption', $option);
            $stmt->execute();
        }
        
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $annonces = [];
        foreach ($resultats as $row) {
            $idAnnonce = $row['id'];
            if (!isset($annonces[$idAnnonce])) {
                $annonces[$idAnnonce] = [
                    'id' => $row['id'],
                    'titre' => $row['titre'],
                    'prix' => $row['prix'],
                    'ville' => $row['ville'],
                    'surface' => $row['surface'],
                    'nombrePiece' => $row['nombrePiece'],
                    'type' => $row['type'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'images' => []
                ];
            }
            
            if ($row['chemin']) {
                $annonces[$idAnnonce]['images'][] = $row['chemin'];
            }
        }
    
        $annonces = array_values($annonces);
    
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($annonces, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    if (isset($_GET['action']) && $_GET['action'] === 'annoncesSansFiltres') {
        annoncesSansFiltres($conn, $id);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'annoncesAvecFiltres') {
        annoncesAvecFiltres($conn, $id);
    } else {
        echo json_encode(['error' => 'Action inconnue'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }    
?>
