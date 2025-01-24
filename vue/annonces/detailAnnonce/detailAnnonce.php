<?php
    session_start();
?>

<script>
    function retour() {
        window.location.href="./../annonces.php";
    }
</script>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="./detailAnnonce.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <title>annonces-details</title>
    </head>
    <body>
        <?php include "../../navigation/navigation.php"; ?>

        <main>
            <div class="details"></div>
            <div class="map" id="map"></div>
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        </main>
    </body>
</html>

<script>
    function normalizeAddress(complementAdresse, ville, codePostal, departement) {
        const replacements = {
            ' premier': ' 1er',
            ' deuxième': ' 2e',
            ' troisième': ' 3e',
            ' quatrième': ' 4e',
            ' cinquième': ' 5e',
        };

        let address = `${complementAdresse}, ${codePostal} ${ville}, ${departement}`;

        Object.keys(replacements).forEach(key => {
            address = address.replace(new RegExp(key, 'gi'), replacements[key]);
        });

        return address;
    }

    async function getCoordinates(complementAdresse, ville, codePostal, departement) {
        const address = normalizeAddress(complementAdresse, ville, codePostal, departement);
        console.log('Adresse normalisée:', address);
        const encodedAddress = encodeURIComponent(address);
        const url = `https://nominatim.openstreetmap.org/search?q=${encodedAddress}&format=json&addressdetails=1`;

        try {
            const response = await fetch(url);
            const data = await response.json();

            if (data && data.length > 0) {
                const { lat, lon } = data[0];
                return { lat, lon };
            } else {
                console.log('Adresse exacte non trouvée, tentative avec la ville...');
                const fallbackUrl = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(ville)}&format=json&addressdetails=1`;

                const fallbackResponse = await fetch(fallbackUrl);
                const fallbackData = await fallbackResponse.json();

                if (fallbackData && fallbackData.length > 0) {
                    const { lat, lon } = fallbackData[0];
                    return { lat, lon };
                } else {
                    throw new Error('Adresse ou ville non trouvée');
                }
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des coordonnées:', error);
            return null;
        }
    }

    function type(annonceDetails) {
        if (annonceDetails.type == 0) {
            return 'LOCATION';
        } else {
            return 'ACHAT';
        }
    }

    function getClasseEnergetiqueClass(classeEnergetique) {
        switch (classeEnergetique) {
            case 'A':
                return 'classe-A';
            case 'B':
                return 'classe-B';
            case 'C':
                return 'classe-C';
            case 'D':
                return 'classe-D';
            case 'E':
                return 'classe-E';
            case 'F':
                return 'classe-F';
            case 'G':
                return 'classe-G';
            default:
                return 'classe-inconnue';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const idAnnonce = new URLSearchParams(window.location.search).get('id');

        if (!idAnnonce) {
            window.location.href = "./../../404/error.php";
            return;
        }

        fetch(`./../../../controller/detailsAnnonceController.php?id=${idAnnonce}`)
            .then(response => {
                if (!response.ok) {
                    window.location.href = "./../../404/error.php";
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    window.location.href = "./../../404/error.php";
                    return;
                }

                const annonce = data[0];
                const complementAdresse = annonce.complementAdresse;
                const ville = annonce.ville;
                const codePostal = annonce.codePostal;
                const departement = annonce.departement;

                getCoordinates(complementAdresse, ville, codePostal, departement)
                .then(coordinates => {
                    if (coordinates) {
                        const { lat, lon } = coordinates;
                        var map = L.map('map').setView([lat, lon], 18);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        var marker = L.marker([lat, lon]).addTo(map);
                        marker.bindPopup(`<b>${annonce.titre}</b><br><b>${type(annonce)}</b><br><b>${annonce.prix} €${type(annonce) == 'LOCATION' ? ' / mois' : ''}</b><br><b>----------</b><br><b>latitude ${lat}</b><br><b>longitude ${lon}</b>`).openPopup();
                    } else {
                        console.log('Coordonnées non trouvées');
                    }
                })

                const annoncesContainer = document.querySelector('.details');
                const htmlContent = `
                    <div class="navRetour">
                        <a onclick="retour()" class="bouton">&#x276E; Retour</a>
                        <button>ICI BOUTON LIKE</button>
                    </div>

                    <div class="detailAnnonce">
                        <div class="entete">
                            <h1 id="titre">${annonce.titre}</h1>
                            <p id="type">${type(annonce)}</p>
                            <p id="adresse">${annonce.codePostal} ${annonce.ville.toUpperCase()} (${annonce.departement.toUpperCase()})</p>
                            <h2 id="prix">${annonce.prix} €${type(annonce) == 'LOCATION' ? ' / mois' : ''}</h2>

                            ${type(annonce) === 'LOCATION' ? 
                                "<a id='estimation' href='https://www.visale.fr'>Trouvez votre garant</a>" : 
                                "<a id='estimation' href='https://e-immobilier.credit-agricole.fr/simulca/pages/ptenational_dt?ORI=EDEBIAT0&at_medium=affiliation&at_campaign=EDE_PC_EMP_EIMMO_FR&at_presta=bienici&at_format=fiche_bien_haut#/?seq=screen01'>Estimez votre mensualité</a>"}
                        </div>

                        <div class="photos">
                            <section class="carousel" aria-label="Gallery">
                                <ol class="carousel__viewport">
                                    ${
                                        annonce.images && annonce.images.length > 0 
                                        ? annonce.images.map((image, index) => {
                                            const currentSlide = index + 1;
                                            const nextSlide = (index + 1) % annonce.images.length + 1;
                                            const prevSlide = (index - 1 + annonce.images.length) % annonce.images.length + 1;
                                            return `
                                                <li id="carousel__slide${currentSlide}" tabindex="0" class="carousel__slide">
                                                    <div class="carousel__snapper">
                                                        <img src="../../../style/${image}" alt="Image ${currentSlide}" />
                                                        <a href="#carousel__slide${prevSlide}" class="carousel__prev">Go to previous slide</a>
                                                        <a href="#carousel__slide${nextSlide}" class="carousel__next">Go to next slide</a>
                                                    </div>
                                                </li>
                                            `;
                                        }).join('')
                                        : `
                                            <li id="carousel__slide1" tabindex="0" class="carousel__slide">
                                                <div class="carousel__snapper">
                                                    <img src="../../../style/noImage.jpg" alt="No image available" />
                                                </div>
                                            </li>
                                        `
                                    }
                                </ol>
                                ${
                                    annonce.images && annonce.images.length > 0 
                                    ? `
                                        <aside class="carousel__navigation">
                                            <ol class="carousel__navigation-list">
                                                ${annonce.images.map((image, index) => {
                                                    const slideNumber = index + 1;
                                                    return `
                                                        <li class="carousel__navigation-item">
                                                            <a href="#carousel__slide${slideNumber}" class="carousel__navigation-button">Go to slide ${slideNumber}</a>
                                                        </li>
                                                    `;
                                                }).join('')}
                                            </ol>
                                        </aside>
                                    `
                                    : ''
                                }
                            </section>
                        </div>

                        <div class="descriptif">
                            <h3>
                                DESCRIPTIF DU LOGEMENT A ${type(annonce) === "LOCATION" ? "LOUER" : "VENDRE"} 
                                DE ${annonce.nombrePiece}
                                ${annonce.nombrePiece > 1 ? 'PIECES' : 'PIECE'}
                                ET ${annonce.surface} M²
                            </h3>

                            <p id="description">${annonce.description}</p>
                        </div>
                        
                        <div class="table-container">
                            <table class="styled-table">
                                <tbody>
                                    <tr>
                                        <td>${annonce.surface} m²</td>
                                        <td>${type(annonce) === "LOCATION" ? "Location" : "Achat"}</td>
                                    </tr>
                                    <tr>
                                        <td>${annonce.nombrePiece} ${annonce.nombrePiece > 1 ? 'pièces' : 'pièce'}</td>
                                        <td>${annonce.chambre} ${annonce.chambre > 1 ? 'chambres' : 'chambre'}</td>
                                    </tr>
                                    <tr>
                                        <td>${annonce.cuisine} ${annonce.cuisine > 1 ? 'cuisines' : 'cuisine'}</td>
                                        <td>${annonce.salon} salon</td>
                                    </tr>
                                    <tr>
                                        <td>${annonce.toilette} ${annonce.toilette > 1 ? 'toilettes' : 'toilette'}</td>
                                        <td>${annonce.salleDeBain} ${annonce.salleDeBain > 1 ? 'salles de bain' : 'salle de bain'}</td>
                                    </tr>
                                    <tr>
                                        <td>${annonce.balcon} ${annonce.balcon > 1 ? 'balcons' : 'balcon'}</td>
                                        <td>${annonce.terrasse} ${annonce.terrasse > 1 ? 'terrasses' : 'terrasse'}</td>
                                    </tr>
                                    <tr>
                                        <td>${annonce.grenier} ${annonce.grenier > 1 ? 'greniers' : 'grenier'}</td>
                                        <td>${annonce.cave} ${annonce.cave > 1 ? 'caves' : 'cave'}</td>
                                    </tr>
                                    <tr>
                                        <td>${annonce.garage} ${annonce.garage > 1 ? 'garages' : 'garage'}</td>
                                        <td>${annonce.parking ? 'parking : OUI' : 'parking : NON'}</td>
                                    </tr>
                                    <tr>
                                        <td>DPE : ${annonce.datedpe}</td>
                                        <td class="${getClasseEnergetiqueClass(annonce.classeEnergetique)}">
                                            Classe énergétique : ${annonce.classeEnergetique}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="contacts">
                            <h3>CONTACT :</h3>
                            <div id="infos">
                                <p><strong>Nom :</strong> ${annonce.utilisateur.nom}</p>
                                <p><strong>Prénom :</strong> ${annonce.utilisateur.prenom}</p>
                                <p><strong>Email :</strong> <a href="mailto:${annonce.utilisateur.mail}">${annonce.utilisateur.mail}</a></p>
                                <p><strong>Téléphone :</strong> <a href="tel:${annonce.utilisateur.tel}">${annonce.utilisateur.tel}</a></p>
                            </div>
                        </div>
                    </div>
                `;
                annoncesContainer.innerHTML = htmlContent;
            })
            .catch(error => {
                console.error('Erreur dans le fetch:', error);
            });
    });
</script>