<?php
    session_start();
    include("./database.php");

    $id = $_SESSION['connectedUser'];

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    function annoncesSansFiltres($conn, $id) {
        $query = "SELECT a.*, i.chemin FROM annonce a LEFT JOIN images i ON a.id = i.idAnnonce WHERE a.idUtilisateur = :userid";
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
        $trie = isset($_GET['trie']) ? $_GET['trie'] : null;
        $prix = isset($_GET['prix']) ? $_GET['prix'] : null;
    
        $sqlOption = "";
        $sqlOrderBy = "";
    
        if ($option === "all") {
            $sqlOption = "AND a.type IN (0, 1)";
        } elseif ($option === "1") {
            $sqlOption = "AND a.type = 1";
        } elseif ($option === "0") {
            $sqlOption = "AND a.type = 0";
        }
    
        if ($trie === "all" && $prix === "all") {
            $sqlOrderBy = "";
        } elseif ($trie === "1" && $prix === "1") {
            $sqlOrderBy = "ORDER BY a.date_creation DESC, a.prix DESC";
        } elseif ($trie === "0" && $prix === "0") {
            $sqlOrderBy = "ORDER BY a.date_creation ASC, a.prix ASC";
        } elseif ($trie === "1" && $prix === "0") {
            $sqlOrderBy = "ORDER BY a.date_creation DESC, a.prix ASC";
        } elseif ($trie === "0" && $prix === "1") {
            $sqlOrderBy = "ORDER BY a.date_creation ASC, a.prix DESC";
        } elseif ($trie === "all" && $prix === "1") {
            $sqlOrderBy = "ORDER BY a.prix DESC";
        } elseif ($trie === "1" && $prix === "all") {
            $sqlOrderBy = "ORDER BY a.date_creation DESC";
        } elseif ($trie === "0" && $prix === "all") {
            $sqlOrderBy = "ORDER BY a.date_creation ASC";
        } elseif ($trie === "all" && $prix === "0") {
            $sqlOrderBy = "ORDER BY a.prix ASC";
        }
    
        $query = "SELECT a.*, i.chemin 
                  FROM annonce a 
                  LEFT JOIN images i ON a.id = i.idAnnonce 
                  WHERE a.idUtilisateur = :userid 
                  AND (:ville = '' OR a.ville = :ville) 
                  $sqlOption 
                  $sqlOrderBy";
    
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userid', $id);
        $stmt->bindParam(':ville', $ville);
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
