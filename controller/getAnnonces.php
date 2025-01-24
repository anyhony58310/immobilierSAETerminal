<?php
    session_start();
    include("./database.php");

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    function annoncesSansFiltres($conn) {
        $query = "SELECT a.*, i.chemin FROM annonce a LEFT JOIN images i ON a.id = i.idAnnonce";
        $stmt = $conn->prepare($query);
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

    function annoncesAvecFiltres($conn) {
        $ville = isset($_GET['ville']) ? $_GET['ville'] : '';
        $option = isset($_GET['option']) ? $_GET['option'] : null;
        $codePostal = isset($_GET['codePostal']) ? $_GET['codePostal'] : null;
        $surface = isset($_GET['surface']) ? $_GET['surface'] : null;
        $prixMin = isset($_GET['prixMin']) ? $_GET['prixMin'] : null;
        $prixMax = isset($_GET['prixMax']) ? $_GET['prixMax'] : null;
        $triePrix = isset($_GET['triePrix']) ? $_GET['triePrix'] : null;
        $trieDate = isset($_GET['trieDate']) ? $_GET['trieDate'] : null;
        $trieEnergetique = isset($_GET['trieEnergetique']) ? $_GET['trieEnergetique'] : null;
        $nombrePiecesMin = isset($_GET['nombrePiecesMin']) ? $_GET['nombrePiecesMin'] : null;
        $nombrePiecesMax = isset($_GET['nombrePiecesMax']) ? $_GET['nombrePiecesMax'] : null;
        $parking = isset($_GET['parking']) ? $_GET['parking'] : null;
        $balcon = isset($_GET['balcon']) ? $_GET['balcon'] : null;
        $terrasse = isset($_GET['terrasse']) ? $_GET['terrasse'] : null;
        $cave = isset($_GET['cave']) ? $_GET['cave'] : null;
        $grenier = isset($_GET['grenier']) ? $_GET['grenier'] : null;
        $garage = isset($_GET['garage']) ? $_GET['garage'] : null;
        $nombreChambre = isset($_GET['nombreChambre']) ? $_GET['nombreChambre'] : null;
        $typeMeuble = isset($_GET['typeMeuble']) ? $_GET['typeMeuble'] : null;

        $requetesOrderBy = "";
        $requeteOption = "";
        $requeteTrieParking = "";
        $requeteTrieBalcon = "";
        $requeteTrieTerrasse = "";
        $requeteTrieCave = "";
        $requeteTrieGrenier = "";
        $requeteTrieGarage = "";
        $trieClasseEnergetique = "";
        $trieMeuble = "";

        if ($triePrix === "all" && $trieDate === "all") {
            $requetesOrderBy = "";
        }
        elseif ($triePrix === "1" && $trieDate === "1") {
            $requetesOrderBy = "ORDER BY a.prix DESC, a.date_creation DESC";
        }
        elseif ($triePrix === "0" && $trieDate === "0") {
            $requetesOrderBy = "ORDER BY a.prix ASC, a.date_creation ASC";
        }
        elseif ($triePrix === "1" && $trieDate === "0") {
            $requetesOrderBy = "ORDER BY a.prix DESC, a.date_creation ASC";
        }
        elseif ($triePrix === "0" && $trieDate === "1") {
            $requetesOrderBy = "ORDER BY a.prix ASC, a.date_creation DESC";
        }
        elseif ($triePrix === "all" && $trieDate === "1") {
            $requetesOrderBy = "ORDER BY a.date_creation DESC";
        }
        elseif ($triePrix === "all" && $trieDate === "0") {
            $requetesOrderBy = "ORDER BY a.date_creation ASC";
        }
        elseif ($triePrix === "0" && $trieDate === "all") {
            $requetesOrderBy = "ORDER BY a.prix ASC";
        }
        elseif ($triePrix === "1" && $trieDate === "all") {
            $requetesOrderBy = "ORDER BY a.prix DESC";
        }
        
        if ($trieEnergetique === "all") {
            $trieClasseEnergetique = "";
        }
        elseif ($trieEnergetique === "a") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'A'";
        }
        elseif ($trieEnergetique === "b") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'B'";
        }
        elseif ($trieEnergetique === "c") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'C'";
        }
        elseif ($trieEnergetique === "d") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'D'";
        }
        elseif ($trieEnergetique === "e") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'E'";
        }
        elseif ($trieEnergetique === "f") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'F'";
        }
        elseif ($trieEnergetique === "g") {
            $trieClasseEnergetique = "AND a.classeEnergetique = 'G'";
        }

        if ($option === "all") {
            $requeteOption = "AND a.type IN (0, 1)";
        }
        elseif ($option === "1") {
            $requeteOption = "AND a.type = 1";
        }
        elseif ($option === "0") {
            $requeteOption = "AND a.type = 0";
        }

        if ($parking === "all") {
            $requeteTrieParking = "AND a.parking IN (0, 1)";
        }
        elseif ($parking === "1") {
            $requeteTrieParking = "AND a.parking = 1";
        }
        elseif ($parking === "0") {
            $requeteTrieParking = "AND a.parking = 0";
        }

        if ($balcon === "all") {
            $requeteTrieBalcon = "AND a.balcon IN (0, 1)";
        }
        elseif ($balcon === "1") {
            $requeteTrieBalcon = "AND a.balcon = 1";
        }
        elseif ($balcon === "0") {
            $requeteTrieBalcon = "AND a.balcon = 0";
        }

        if ($terrasse === "all") {
            $requeteTrieTerrasse = "AND a.terrasse IN (0, 1)";
        }
        elseif ($terrasse === "1") {
            $requeteTrieTerrasse = "AND a.terrasse = 1";
        }
        elseif ($terrasse === "0") {
            $requeteTrieTerrasse = "AND a.terrasse = 0";
        }

        if ($cave === "all") {
            $requeteTrieCave = "AND a.cave IN (0, 1)";
        }
        elseif ($cave === "1") {
            $requeteTrieCave = "AND a.cave = 1";
        }
        elseif ($cave === "0") {
            $requeteTrieCave = "AND a.cave = 0";
        }

        if ($grenier === "all") {
            $requeteTrieGrenier = "AND a.grenier IN (0, 1)";
        }
        elseif ($grenier === "1") {
            $requeteTrieGrenier = "AND a.grenier = 1";
        }
        elseif ($grenier === "0") {
            $requeteTrieGrenier = "AND a.grenier = 0";
        }

        if ($garage === "all") {
            $requeteTrieGarage = "AND a.garage IN (0, 1)";
        }
        elseif ($garage === "1") {
            $requeteTrieGarage = "AND a.garage = 1";
        }
        elseif ($garage === "0") {
            $requeteTrieGarage = "AND a.garage = 0";
        }

        if ($typeMeuble === "all") {
            $trieMeuble = "";
        }
        elseif ($typeMeuble === "1") {
            $trieMeuble = "AND a.meuble = 1";
        }
        elseif ($typeMeuble === "0") {
            $trieMeuble = "AND a.meuble = 0";
        }

        $query = "SELECT a.*, i.chemin FROM annonce a LEFT JOIN images i ON a.id = i.idAnnonce 
        WHERE (:ville = '' OR a.ville = :ville)
        AND (:codePostal = '' OR a.codePostal = :codePostal)
        AND (:surface = '' OR a.surface = :surface)
        AND (:prixMin = '' OR a.prix >= :prixMin)
        AND (:prixMax = '' OR a.prix <= :prixMax)
        AND (:nombrePiecesMin = '' OR a.nombrePiece >= :nombrePiecesMin)
        AND (:nombrePiecesMax = '' OR a.nombrePiece <= :nombrePiecesMax)
        AND (:nombreChambre = '' OR a.chambre = :nombreChambre)
        $requeteOption
        $requeteTrieParking
        $requeteTrieBalcon
        $requeteTrieTerrasse
        $requeteTrieCave
        $requeteTrieGrenier
        $requeteTrieGarage
        $trieClasseEnergetique
        $trieMeuble
        $requetesOrderBy";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':codePostal', $codePostal);
        $stmt->bindParam(':surface', $surface);
        $stmt->bindParam(':prixMin', $prixMin);
        $stmt->bindParam(':prixMax', $prixMax);
        $stmt->bindParam(':nombrePiecesMin', $nombrePiecesMin);
        $stmt->bindParam(':nombrePiecesMax', $nombrePiecesMax);
        $stmt->bindParam(':nombreChambre', $nombreChambre);
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
    
    if (isset($_GET['action']) && $_GET['action'] === 'annoncesSansFiltres') {
        annoncesSansFiltres($conn);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'annoncesAvecFiltres') {
        annoncesAvecFiltres($conn);
    } else {
        echo json_encode(['error' => 'Action inconnue'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }    
?>
