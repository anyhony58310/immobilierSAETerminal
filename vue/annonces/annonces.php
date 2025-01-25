<?php
    session_start();
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
            const response = await fetch('../../controller/getAnnonces.php?action=annoncesSansFiltres');
            if (!response.ok) throw new Error('Erreur lors de la récupération des données');

            const data = await response.json();
            renderAnnonces(data);
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    async function annoncesAvecFiltres(ville, option, codePostal, surface, prixMin, prixMax, triePrix, trieDate, trieEnergetique, nombrePiecesMin, nombrePiecesMax, parking, balcon, terrasse, cave, grenier, garage, nombreChambre, typeMeuble) {
        try {
            const response = await fetch(`../../controller/getAnnonces.php?action=annoncesAvecFiltres&ville=${ville}&option=${option}&codePostal=${codePostal}&surface=${surface}&prixMin=${prixMin}&prixMax=${prixMax}&triePrix=${triePrix}&trieDate=${trieDate}&trieEnergetique=${trieEnergetique}&nombrePiecesMin=${nombrePiecesMin}&nombrePiecesMax=${nombrePiecesMax}&parking=${parking}&balcon=${balcon}&terrasse=${terrasse}&cave=${cave}&grenier=${grenier}&garage=${garage}&nombreChambre=${nombreChambre}&typeMeuble=${typeMeuble}`);
            if (!response.ok) throw new Error('Erreur lors de la récupération des données');

            const data = await response.json();
            renderAnnonces(data);
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const toggleButton = document.getElementById("toggle-filtres");
        const advancedFilters = document.getElementById("filtres-avances");

        toggleButton.addEventListener("click", () => {
            if (advancedFilters.style.display === "none") {
                advancedFilters.style.display = "flex";
                toggleButton.textContent = "Réduire les filtres";
            } else {
                advancedFilters.style.display = "none";
                toggleButton.textContent = "Afficher plus de filtres";
            }
        });
        
        let ville = "";
        let option = "";
        let codePostal = "";
        let surface = "";
        let prixMin = "";
        let prixMax = "";
        let triePrix = "";
        let trieDate = "";
        let trieEnergetique = "";
        let nombrePiecesMin = "";
        let nombrePiecesMax = "";
        let parking = "";
        let balcon = "";
        let terrasse = "";
        let cave = "";
        let grenier = "";
        let garage = "";
        let nombreChambre = "";
        let typeMeuble = "";

        annoncesSansFiltres();

        document.getElementById('rechercher').addEventListener('click', () => {
            const ville = document.getElementById('search-input').value.trim();
            const option = document.getElementById('choix').value;
            const codePostal = document.getElementById('codePostal').value;
            const surface = document.getElementById('surface').value;
            const prixMin = document.getElementById('prixMin').value;
            const prixMax = document.getElementById('prixMax').value;
            const triePrix = document.getElementById('triePrix').value;
            const trieDate = document.getElementById('trieDate').value;
            const trieEnergetique = document.getElementById('trieEnergetique').value;
            const nombrePiecesMin = document.getElementById('nombrePiecesMin').value;
            const nombrePiecesMax = document.getElementById('nombrePiecesMax').value;
            const parking = document.getElementById('parking').value;
            const balcon = document.getElementById('balcon').value;
            const terrasse = document.getElementById('terrasse').value;
            const cave = document.getElementById('cave').value;
            const grenier = document.getElementById('grenier').value;
            const garage = document.getElementById('garage').value;
            const nombreChambre = document.getElementById('nombreChambre').value;
            const typeMeuble = document.getElementById('typeMeuble').value;

            if (ville === "" && option === "all" && codePostal === "" && surface === "" && prixMin === "" && prixMax === "" && triePrix === "all" && trieDate === "all" && trieEnergetique === "all" && nombrePiecesMin === "" && nombrePiecesMax === "" && parking === "all" && balcon === "all" && terrasse === "all" && cave === "all" && grenier === "all" && garage === "all" && nombreChambre === "" && typeMeuble === "all") {
                annoncesSansFiltres();
            } else {
                annoncesAvecFiltres(ville, option, codePostal, surface, prixMin, prixMax, triePrix, trieDate, trieEnergetique, nombrePiecesMin, nombrePiecesMax, parking, balcon, terrasse, cave, grenier, garage, nombreChambre, typeMeuble);
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
        <link rel="stylesheet" href="./annonces.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <title>Annonces</title>
    </head>
    <body>
        <?php include('./../navigation/navigation.php'); ?>

        <main>
            <div class="details">
                <div class="filtres">
                    <div class="groupe basique" id="groupe-basique">
                        <input type="text" id="search-input" placeholder="Rechercher une ville..." />
                        <select id="choix" name="choix">
                            <option value="all">Location & Achat</option>
                            <option value="1">Achat</option>
                            <option value="0">Location</option>
                        </select>
                        <button id="rechercher" class="bouton">🔍</button>
                    </div>
                    <button id="toggle-filtres" class="bouton">Afficher plus de filtres</button>
                    <div class="groupe avancé" id="filtres-avances" style="display: none;">
                        <select id="typeMeuble" name="typeMeuble">
                            <option value="all">meublé & non meublé</option>
                            <option value="1">meublé</option>
                            <option value="0">non meublé</option>
                        </select>
                        <input type="number" id="codePostal" name="trieCodePostal" placeholder="code postal" />
                        <input type="number" id="surface" name="surface" placeholder="surface" />
                        <input type="number" id="prixMin" name="prixMin" placeholder="prix min" />
                        <input type="number" id="prixMax" name="prixMax" placeholder="prix max" />
                        <select id="triePrix" name="triePrix">
                            <option value="all">trie par prix</option>
                            <option value="1">Du + cher</option>
                            <option value="0">Du - cher</option>
                        </select>
                        <select id="trieDate" name="trieDate">
                            <option value="all">trie par date</option>
                            <option value="1">Du + récent</option>
                            <option value="0">Du - récent</option>
                        </select>
                        <select id="trieEnergetique" name="trieEnergetique">
                            <option value="all">classe energetique</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                            <option value="e">E</option>
                            <option value="f">F</option>
                            <option value="g">G</option>
                        </select>
                        <input type="number" id="nombrePiecesMin" name="nombrePiecesMin" placeholder="pièces min" />
                        <input type="number" id="nombrePiecesMax" name="nombrePiecesMax" placeholder="pièces max" />
                        <input type="number" id="nombreChambre" name="nombreChambre" placeholder="nombre chambre" />
                        <select id="parking" name="parking">
                            <option value="all">trie parking</option>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        <select id="balcon" name="balcon">
                            <option value="all">trie balcon</option>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        <select id="terrasse" name="terrasse">
                            <option value="all">trie terrasse</option>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        <select id="cave" name="cave">
                            <option value="all">trie cave</option>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        <select id="grenier" name="grenier">
                            <option value="all">trie grenier</option>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        <select id="garage" name="garage">
                            <option value="all">trie garage</option>
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
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