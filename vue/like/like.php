<?php
    session_start();

    if (!isset($_SESSION['connectedUser'])) {
        header('Location: http://localhost/sitesae/vue/connexion/connexion.php');
        exit();
    }
?>

<script>
    function retour() {
        window.location.href="./../annonces/annonces.php";
    }

    function type(annonce) {
        return annonce.type == 0 ? "LOCATION" : "ACHAT";
    }

    function createAnnonceLink(annonce) {
        const premiereImage = annonce.images?.[0] || 'noImage.jpg';
        const link = document.createElement('a');
        link.href = `http://localhost/sitesae/vue/annonces/detailAnnonce/detailAnnonce.php?id=${annonce.id}`;
        link.classList.add('annonce-link');
        link.innerHTML = `
            <div class="annonce-item">
                <img class="image" src="../../style/${premiereImage}" alt="Image de ${annonce.titre}">
                <h2>${annonce.titre}</h2>
                <p id="type">${type(annonce)}</p>
                <div class="informations">
                    <p>${annonce.surface} m²</p>
                    <p id="ville">${annonce.nombrePiece} pièces</p>
                </div>
                <div class="informations">
                    <p>${annonce.prix} €</p>
                    <p id="ville">${annonce.ville}</p>
                </div>
            </div>
        `;
        return link;
    }

    function clearMarkers() {
        markers.forEach(marker => {
            map.removeLayer(marker);
        });
        markers = [];
    }
    
    function renderAnnonces(data) {
        const annoncesContainer = document.querySelector('.grille');
        const fragment = document.createDocumentFragment();

        const coordinates = [];

        data.forEach(annonce => {
            if (annonce.latitude && annonce.longitude) {
                coordinates.push([annonce.latitude, annonce.longitude, annonce]);
            }

            const link = createAnnonceLink(annonce);
            fragment.appendChild(link);
        });

        annoncesContainer.innerHTML = '';
        annoncesContainer.appendChild(fragment);

        clearMarkers();

        let groupIndex = 0;
        const chunkSize = 10;
        while (groupIndex < coordinates.length) {
            const chunk = coordinates.slice(groupIndex, groupIndex + chunkSize);
            addMarkers(chunk);
            groupIndex += chunkSize;
        }
    }

    function addMarkers(chunk) {
        chunk.forEach(([latitude, longitude, annonce]) => {
            const marker = L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup(`
                    <strong>${annonce.ville}</strong>
                `);
            markers.push(marker);
        });
    }

    async function annoncesSansFiltres() {
        try {
            const response = await fetch('../../controller/getAnnonceLike.php?action=annoncesSansFiltres');
            if (!response.ok) throw new Error('Erreur lors de la récupération des données');

            const data = await response.json();
            renderAnnonces(data);
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    async function annoncesAvecFiltres(ville, option) {
        try {
            const response = await fetch(`../../controller/getAnnonceLike.php?action=annoncesAvecFiltres&ville=${ville}&option=${option}`);
            if (!response.ok) throw new Error('Erreur lors de la récupération des données');

            const data = await response.json();
            renderAnnonces(data);
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        let ville = "";
        let option = "";

        annoncesSansFiltres();

        document.getElementById('rechercher').addEventListener('click', () => {
            ville = document.getElementById('search-input').value.trim();
            option = document.getElementById('choix').value;

            if (ville === "" && option === "all") {
                annoncesSansFiltres();
            } else {
                annoncesAvecFiltres(ville, option);
            }
        });
    });

    let markers = [];
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./like.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <title>Annonces-Favories</title>
</head>
<body>
    <?php include('./../navigation/navigation.php'); ?>

    <main>
        <div class="details">
            <div class="navRetour">
                <a onclick="retour()" class="bouton">&#x276E; Annonces</a>
            </div>
            
            <div class="filtres">
                <div class="groupe">
                    <input type="text" id="search-input" placeholder="Rechercher une ville..." />
                    <select id="choix" name="choix">
                        <option value="all">Location & Achat</option>
                        <option value="1">Achat</option>
                        <option value="0">Location</option>
                    </select>
                    <button id="rechercher" class="bouton">🔍</button>
                </div>
            </div>
            <div class="detailLike">
                <div class="grille"></div>
            </div>
        </div>

        <div class="map" id="map"></div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            var map = L.map('map').setView([46.603354, 1.888334], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        </script>
    </main>
</body>
</html>