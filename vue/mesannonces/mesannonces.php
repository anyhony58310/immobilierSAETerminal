<?php
    session_start();

    if (!isset($_SESSION['connectedUser'])) {
        header('Location: http://localhost/sitesae/vue/connexion/connexion.php');
        exit();
    }
?>

<script>
    function retour() {
        window.location.href = "./../annonces/annonces.php";
    }

    function type(annonce) {
        return annonce.type == 0 ? "LOCATION" : "ACHAT";
    }

    async function supprimerAnnonce(id) {
        try {
            const response = await fetch(`../../controller/supprimerAnnonce.php?id=${id}`);
            const result = await response.json();

            if (result.success) {
                const annonceElement = document.querySelector(`#annonce-${id}`);
                if (annonceElement) {
                    annonceElement.remove();
                }
            } else {
                console.error(result.message || 'Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    function createAnnonceLink(annonce) {
        const premiereImage = annonce.images?.[0] || 'noImage.jpg';
        const link = document.createElement('a');
        link.href = `http://localhost/sitesae/vue/annonces/detailAnnonce/detailAnnonce.php?id=${annonce.id}`;
        link.classList.add('annonce-link');
        link.id = `annonce-${annonce.id}`;
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
                <div class="gererAnnonce">
                    <button id="supprAnnonce" class="supprAnnonce">Supprimer</button>
                </div>
            </div>
        `;

        const supprButton = link.querySelector('#supprAnnonce');
        supprButton.addEventListener('click', (event) => {
            event.stopPropagation();
            event.preventDefault();

            supprimerAnnonce(annonce.id);
        });
        return link;
    }

    function renderAnnonces(data) {
        const annoncesContainer = document.querySelector('.grille');
        const fragment = document.createDocumentFragment();

        data.forEach(annonce => {
            const link = createAnnonceLink(annonce);
            fragment.appendChild(link);
        });

        annoncesContainer.innerHTML = '';
        annoncesContainer.appendChild(fragment);
    }

    async function annoncesSansFiltres() {
        try {
            const response = await fetch('../../controller/mesannonces.php?action=annoncesSansFiltres');
            if (!response.ok) throw new Error('Erreur lors de la récupération des données');

            const data = await response.json();
            renderAnnonces(data);
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    async function annoncesAvecFiltres(ville, option, trie, prix) {
        try {
            const response = await fetch(`../../controller/mesannonces.php?action=annoncesAvecFiltres&ville=${ville}&option=${option}&trie=${trie}&prix=${prix}`);
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
        let trie = "";
        let prix = "";

        annoncesSansFiltres();

        document.getElementById('rechercher').addEventListener('click', () => {
            ville = document.getElementById('search-input').value.trim();
            option = document.getElementById('choix').value;
            trie = document.getElementById('trie').value;
            prix = document.getElementById('prix').value;

            if (ville === "" && option === "all" && trie === "all" && prix === "all") {
                annoncesSansFiltres();
            } else {
                annoncesAvecFiltres(ville, option, trie, prix);
            }
        });
    });
</script>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./mesannonces.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <title>Mes annonces</title>
    </head>
    <body>
        <?php include './../navigation/navigation.php'; ?>

        <div class="filtres">
            <div class="groupe">
                <input type="text" id="search-input" placeholder="Rechercher une ville..." />

                <select id="choix" name="choix">
                    <option value="all">Location & Achat</option>
                    <option value="1">Achat</option>
                    <option value="0">Location</option>
                </select>

                <select id="trie" name="trie">
                    <option value="all">Trie par date</option>
                    <option value="1">Plus récent</option>
                    <option value="0">Moins récent</option>
                </select>

                <select id="prix" name="prix">
                    <option value="all">Trie par prix</option>
                    <option value="1">Plus chère</option>
                    <option value="0">Moins chère</option>
                </select>

                <button id="rechercher" class="bouton">🔍</button>
            </div>
        </div>

        <main>
            <div class="grille"></div>
        </main>
    </body>
</html>