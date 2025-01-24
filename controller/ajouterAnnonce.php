<?php
    session_start();
    include './database.php';

    $id = $_SESSION['connectedUser'];
    $idDerniereAnnonce = "";

    $db = new DatabaseConnexion();
    $conn = $db->connect();

    if (isset($_POST['orderedNames']) && isset($_POST['titre']) && isset($_POST['typeChoix']) && isset($_POST['prix'])  && isset($_POST['meuble']) && isset($_POST['description']) && isset($_POST['complementAdresse']) && isset($_POST['codePostal']) && isset($_POST['ville']) && isset($_POST['departement']) && isset($_POST['surface']) && isset($_POST['nombrePiece']) && isset($_POST['chambre']) && isset($_POST['cuisine']) && isset($_POST['salleDeBain']) && isset($_POST['toilette']) && isset($_POST['salon']) && isset($_POST['garage']) && isset($_POST['terrasse']) && isset($_POST['cave']) && isset($_POST['grenier']) && isset($_POST['parkingChoix']) && isset($_POST['balcon']) && isset($_POST['dateDPE']) && isset($_POST['classeEnergetique']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $orderedNames = json_decode($_POST['orderedNames'], true);
        if (count($orderedNames) <= 0) {
            echo json_encode(["success" => false, "message" => "Au moins une image doit être mise."]);
            exit();
        }

        $titre = $_POST['titre'];
        if (strlen($titre) > 30) {
            echo json_encode(["success" => false, "message" => "Le titre ne peut excéder 30 caractères."]);
            exit();  
        }

        $typeChoix = $_POST['typeChoix'];

        $prix = $_POST['prix'];
        if ($prix <= 0) {
            echo json_encode(["success" => false, "message" => "Le prix ne peut pas être inférieur ou égal à 0."]);
            exit();
        }

        $meuble = $_POST['meuble'];

        $description = $_POST['description'];

        $complementAdresse = $_POST['complementAdresse'];

        $codePostal = $_POST['codePostal'];
        if (strlen($codePostal) != 5) {
            echo json_encode(["success" => false, "message" => "Le code postal n'est pas valide."]);
            exit();
        }

        $ville = $_POST["ville"];

        $departement = $_POST['departement'];

        $surface = $_POST['surface'];
        if ($surface <= 0) {
            echo json_encode(["success" => false, "message" => "La surface ne peut pas être inférieur ou égal à 0."]);
            exit();
        }

        $nombrePiece = $_POST['nombrePiece'];
        if ($nombrePiece <= 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de pièce(s) ne peut pas être inférieur ou égal à 0 m²."]);
            exit();
        }
        
        $chambre = $_POST['chambre'];
        if ($chambre < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de chambre(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $cuisine = $_POST['cuisine'];
        if ($cuisine < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de cuisine(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $salleDeBain = $_POST['salleDeBain'];
        if ($salleDeBain < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de salle(s) de bain ne peut pas être inférieur à 0."]);
            exit();
        }

        $toilette = $_POST['toilette'];
        if ($toilette < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de toilette(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $salon = $_POST['salon'];
        if ($salon < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de salon(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $garage = $_POST['garage'];
        if ($garage < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de garages(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $terrasse = $_POST['terrasse'];
        if ($terrasse < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de terrasses(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $cave = $_POST['cave'];
        if ($cave < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de cave(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $grenier = $_POST['grenier'];
        if ($grenier < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de grenier(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $parkingChoix = $_POST['parkingChoix'];

        $balcon = $_POST['balcon'];
        if ($balcon < 0) {
            echo json_encode(["success" => false, "message" => "Le nombre de balcon(s) ne peut pas être inférieur à 0."]);
            exit();
        }

        $datedpe = $_POST['dateDPE'];

        $classeEnergetique = $_POST['classeEnergetique'];

        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];


        $checkNombrePiecesTotal = $chambre + $salon + $salleDeBain + $cuisine;
        if ($nombrePiece != $checkNombrePiecesTotal) {
            echo json_encode(["success" => false, "message" => "Le nombre de pièces totales ne correspond pas avec le nombre de pièces entrer."]);
            exit();
        }

        $ajouteAnnonce = "INSERT INTO annonce(titre, prix, description, complementAdresse, codePostal, ville, departement, idUtilisateur, type, surface, nombrePiece, chambre, cuisine, salleDeBain, toilette, salon, garage, terrasse, cave, grenier, parking, balcon, datedpe, classeEnergetique, meuble, latitude, longitude) VALUES(:titre, :prix, :description, :complementAdresse, :codePostal, :ville, :departement, :id, :type, :surface, :nombrePiece, :chambre, :cuisine, :salleDeBain, :toilette, :salon, :garage, :terrasse, :cave, :grenier, :parking, :balcon, :datedpe, :classeEnergetique, :meuble, :latitude, :longitude)";
        $stmt = $conn->prepare($ajouteAnnonce);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':complementAdresse', $complementAdresse);
        $stmt->bindParam(':codePostal', $codePostal);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':departement', $departement);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $typeChoix);
        $stmt->bindParam(':surface', $surface);
        $stmt->bindParam(':nombrePiece', $nombrePiece);
        $stmt->bindParam(':chambre', $chambre);
        $stmt->bindParam(':cuisine', $cuisine);
        $stmt->bindParam(':salleDeBain', $salleDeBain);
        $stmt->bindParam(':toilette', $toilette);
        $stmt->bindParam(':salon', $salon);
        $stmt->bindParam(':garage', $garage);
        $stmt->bindParam(':terrasse', $terrasse);
        $stmt->bindParam(':cave', $cave);
        $stmt->bindParam(':grenier', $grenier);
        $stmt->bindParam(':parking', $parkingChoix);
        $stmt->bindParam(':balcon', $balcon);
        $stmt->bindParam(':datedpe', $datedpe);
        $stmt->bindParam(':classeEnergetique', $classeEnergetique);
        $stmt->bindParam(':meuble', $meuble);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);

        if ($stmt->execute()) {
            $derniereAnnonce = "SELECT id FROM annonce WHERE idUtilisateur = :id ORDER BY date_creation DESC LIMIT 1";
            $stmt = $conn->prepare($derniereAnnonce);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $idDerniereAnnonce = $result['id'];
            
                if (is_array($orderedNames)) {
                    foreach ($orderedNames as $name) {
                        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (!in_array($extension, ['jpg', 'png', 'gif', 'jpeg'])) {
                            $supprimerDerniereAnnonce = "DELETE FROM annonce WHERE id = :id";
                            $stmt = $conn->prepare($supprimerDerniereAnnonce);
                            $stmt->bindParam(':id', $idDerniereAnnonce);
                            $stmt->execute();
                            
                            echo json_encode(["success" => false, "message" => "Format de fichier non valide. Seuls les fichiers JPG, PNG, GIF et JPEG sont acceptés."]);
                            exit();
                        }

                        $ajoutImage = "INSERT INTO images(chemin, idAnnonce) VALUES(:chemin, :idAnnonce)";
                        $stmt = $conn->prepare($ajoutImage);
                        $stmt->bindParam(':chemin', $name);
                        $stmt->bindParam(':idAnnonce', $idDerniereAnnonce);

                        if (!$stmt->execute()) {
                            $supprimerDerniereAnnonce = "DELETE FROM annonce WHERE id = :id";
                            $stmt = $conn->prepare($supprimerDerniereAnnonce);
                            $stmt->bindParam(':id', $idDerniereAnnonce);
                            $stmt->execute();
                        
                            echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de l'annonce - ANNONCE SUPPRIMEE."]);
                            exit();
                        }
                    }
                    echo json_encode(["success" => true, "redirect" => "http://localhost/sitesae/vue/mesannonces/mesannonces.php"]);
                    exit();
                }
                else {
                    $supprimerDerniereAnnonce = "DELETE FROM annonce WHERE id = :id";
                    $stmt = $conn->prepare($supprimerDerniereAnnonce);
                    $stmt->bindParam(':id', $idDerniereAnnonce);
                    $stmt->execute();
                
                    echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de l'annonce - ANNONCE SUPPRIMEE."]);
                    exit();
                }
            }                               
            else {            
                echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de l'annonce"]);
                exit();
            }            
        }
        else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de l'annonce."]);
            exit();
        }

    }
?>