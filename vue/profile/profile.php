<?php
    session_start();
    
    if (!isset($_SESSION['connectedUser'])) {
        header('Location: http://localhost/sitesae/vue/connexion/connexion.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profil</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="./profile.css">
    </head>
    <body>
        <?php include './../navigation/navigation.php'; ?>

        <main>
            <div class="infosProfile">
                <h1>Votre Profil</h1>
                <div class="profileCards"></div>
                <div class="profileActions">
                    <button id="annoncesBtn">Annonces</button>
                    <button id="favorisBtn">Favoris</button>
                    <button id="mesAnnoncesBtn">Mes Annonces</button>
                    <button id="changermdp">Changer MDP</button>
                    <button id="deconnexionBtn">Déconnexion</button>
                    <button id="desinscription">Désinscription</button>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                fetch('../../controller/profileController.php')
                    .then(response => {
                        if (!response.ok) {
                            window.location.href = './../404/error.php';
                            return;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data || data.error) {
                            window.location.href = './../404/error.php';
                            return;
                        }

                        const profileContainer = document.querySelector('.profileCards');
                        
                        let htmlContent = ``;
                        data.forEach(user => {
                            htmlContent += `
                                <div class="profileCard">
                                    <div class="header">
                                        <h2>${user.nom.toUpperCase()} ${user.prenom}</h2>
                                        <a href="./modifierProfile/modifierProfile.php">Modifier Profil</a>
                                    </div>
                                    <div class="details">
                                        <p><strong>Pseudo:</strong> ${user.pseudo}</p>
                                        <p><strong>Email:</strong> ${user.mail}</p>
                                        <p><strong>Téléphone:</strong> ${user.tel}</p>
                                        <p><strong>Vous possédez </strong> ${user.annonces} <strong>annonces</strong></p>
                                    </div>
                                </div>
                            `;
                        });

                        profileContainer.innerHTML = htmlContent;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des données :', error);
                    });

                document.getElementById('annoncesBtn').addEventListener('click', () => {
                    window.location.href="./../annonces/annonces.php";
                });
                document.getElementById('favorisBtn').addEventListener('click', () => {
                    window.location.href="./../like/like.php";
                });
                document.getElementById('mesAnnoncesBtn').addEventListener('click', () => {
                    window.location.href="./../mesannonces/mesannonces.php";
                });

                document.getElementById('deconnexionBtn').addEventListener('click', () => {
                    window.location.href = "http://localhost/sitesae/vue/deconnexion/deconnexion.php";
                });

                document.getElementById('changermdp').addEventListener('click', () => {
                    window.location.href = "http://localhost/sitesae/vue/profile/changermdp/changermdp.php";
                });

                document.getElementById('desinscription').addEventListener('click', () => {
                    window.location.href = "http://localhost/sitesae/vue/profile/desinscription/desinscription.php";
                });
            });
        </script>
    </body>
</html>
