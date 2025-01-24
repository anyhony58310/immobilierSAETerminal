<?php
    session_start();
    include 'database.php';

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    $idAnnonce = isset($_GET['id']) ? intval($_GET['id']) : null;

    $query = "SELECT a.*, i.chemin, f.annonceid,
                    u.nom AS utilisateur_nom, 
                    u.prenom AS utilisateur_prenom, 
                    u.pseudo AS utilisateur_pseudo, 
                    u.mail AS utilisateur_mail, 
                    u.tel AS utilisateur_tel
            FROM annonce a
            LEFT JOIN images i ON a.id = i.idAnnonce
            LEFT JOIN utilisateurs u ON a.idUtilisateur = u.id
            LEFT JOIN favories f ON a.id = f.annonceid
            WHERE a.id = :idAnnonce";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idAnnonce', $idAnnonce, PDO::PARAM_INT);
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
                'description' => $row['description'],
                'complementAdresse' => $row['complementAdresse'],
                'codePostal' => $row['codePostal'],
                'ville' => $row['ville'],
                'departement' => $row['departement'],
                'idUtilisateur' => $row['idUtilisateur'],
                'surface' => $row['surface'],
                'nombrePiece' => $row['nombrePiece'],
                'type' => $row['type'],
                'chambre' => $row['chambre'],
                'cuisine' => $row['cuisine'],
                'salleDeBain' => $row['salleDeBain'],
                'toilette' => $row['toilette'],
                'salon' => $row['salon'],
                'garage' => $row['garage'],
                'terrasse' => $row['terrasse'],
                'cave' => $row['cave'],
                'grenier' => $row['grenier'],
                'balcon' => $row['balcon'],
                'parking' => $row['parking'],
                'datedpe' => $row['datedpe'],
                'classeEnergetique' => $row['classeEnergetique'],
                'annonceid' => $row['annonceid'],
                'utilisateur' => [
                    'nom' => $row['utilisateur_nom'],
                    'prenom' => $row['utilisateur_prenom'],
                    'pseudo' => $row['utilisateur_pseudo'],
                    'mail' => $row['utilisateur_mail'],
                    'tel' => $row['utilisateur_tel']
                ],
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
?>