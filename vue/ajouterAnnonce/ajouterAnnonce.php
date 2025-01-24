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
        <title>ajouter-annonce</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./ajouterAnnonce.css">
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    </head>
    <body>
        <?php include './../navigation/navigation.php'; ?>

        <main>
            <form id="newAnnonce" method="POST" enctype="multipart/form-data">

                <label for="titre">Titre annonce:</label>
                <input type="text" id="titre" name="titre" placeholder="Appartement étudiant" required>

                <label for="typeChoix">Achat ou location:</label>
                <select id="typeChoix" name="typeChoix" required>
                    <option value="" disabled selected>-- Choisir achat ou location --</option>
                    <option value="1">Achat</option>
                    <option value="0">Location</option>
                </select>
                
                <label for="prix">Prix (€):</label>
                <input type="number" id="prix" name="prix" placeholder="1000" required>

                <label for="meuble">Meublé ou non meublé:</label>
                <select id="meuble" name="meuble" required>
                    <option value="" disabled selected>-- Choisir meublé ou non --</option>
                    <option value="1">Meublé</option>
                    <option value="0">Non meublé</option>
                </select>

                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Décrivez votre logement dans les détails..."></textarea>

                <label for="complementAdresse">Complément adresse:</label>
                <input type="text" id="complementAdresse" name="complementAdresse" placeholder="numéro de rue et nom de rue complète" required>

                <label for="codePostal">Code postal:</label>
                <input type="number" id="codePostal" name="codePostal" placeholder="xxxxx" required>

                <label for="ville">Ville:</label>
                <input type="text" id="ville" name="ville" placeholder="Nevers" required>

                <label for="departement">Département:</label>
                <input type="text" id="departement" name="departement" placeholder="Nièvre" required>

                <label for="surface">Surface (m²):</label>
                <input type="number" id="surface" name="surface" placeholder="29" required>

                <label for="nombrePiece">Nombre pièce(s):</label><span class="tooltip">infos</span>
                <input type="number" id="nombrePiece" name="nombrePiece" placeholder="3" required>

                <label for="chambre">Nombre de chambre(s):</label>
                <input type="number" id="chambre" name="chambre" placeholder="1" required>

                <label for="cuisine">Nombre de cuisine(s):</label>
                <input type="number" id="cuisine" name="cuisine" placeholder="1" required>

                <label for="salleDeBain">Nombre de salle de bain(s):</label>
                <input type="number" id="salleDeBain" name="salleDeBain" placeholder="1" required>

                <label for="toilette">Nombre de toilette(s):</label>
                <input type="number" id="toilette" name="toilette" placeholder="1" required>

                <label for="salon">Nombre de salon(s):</label>
                <input type="number" id="salon" name="salon" placeholder="1" required>

                <label for="garage">Nombre de garage(s):</label>
                <input type="number" id="garage" name="garage" placeholder="1" required>

                <label for="terrasse">Nombre de terrasse(s):</label>
                <input type="number" id="terrasse" name="terrasse" placeholder="1" required>

                <label for="cave">Nombre de cave(s):</label>
                <input type="number" id="cave" name="cave" placeholder="1" required>

                <label for="grenier">Nombre de grenier(s):</label>
                <input type="number" id="grenier" name="grenier" placeholder="1" required>

                <label for="parkingChoix">Parking:</label>
                <select id="parkingChoix" name="parkingChoix" required>
                    <option value="" disabled selected>-- Oui / Non --</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select> 

                <label for="balcon">Nombre de balcon(s):</label>
                <input type="number" id="balcon" name="balcon" placeholder="1" required>

                <label for="dateDPE">Date du dernier DPE:</label>
                <input type="date" id="dateDPE" name="dateDPE" placeholder="AAAA-MM-DD" required>

                <label for="classeEnergetique">Classe énergétique:</label>
                <select id="classeEnergetique" name="classeEnergetique" required>
                    <option value="" disabled selected>-- Classe énergétique --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                </select>
                
                <label for="photos">Ajoutez des photos:</label>
                <input type="file" id="photos" name="images" multiple accept="image/*">

                <div id="imageList"></div>

                <p id="erreur"></p>
                
                <div style="display: flex; justify-content: space-between;">
                    <button id="envoieFormulaire" type="submit" name="update">Enregistrer</button>
                    <button id="annulerFormulaire" type="button" onclick="window.location.href = './../annonces/annonces.php';">Annuler</button>
                </div>
            </form>
        </main>
    </body>
</html>

<script>
    async function getCoordinates(ville) {
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(ville)}&format=json&addressdetails=1&limit=1`;

        try {
            const response = await fetch(url);
            const data = await response.json();

            if (data && data.length > 0) {
                const { lat, lon } = data[0];
                return { lat, lon };
            } else {
                throw new Error('Ville non trouvée');
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des coordonnées:', error);
            return null;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('photos');
        const imageList = document.getElementById('imageList');

        input.addEventListener('change', (event) => {
            imageList.innerHTML = '';

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.setAttribute('data-index', index);
                    img.classList.add('thumbnail');
                    imageList.appendChild(img);
                };

                reader.readAsDataURL(file);
            });

            new Sortable(imageList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onUpdate: () => {
                    const orderedNames = Array.from(imageList.children).map(img => {
                        const index = img.getAttribute('data-index');
                        return input.files[index].name;
                    });
                    console.log('Ordre actuel des images (par nom) :', orderedNames);
                }
            });
        });

        document.getElementById('newAnnonce').addEventListener('submit', async function(event) {
            event.preventDefault();

            const orderedNames = Array.from(imageList.children).map(img => {
                const index = img.getAttribute('data-index');
                return input.files[index].name;
            });

            console.log('Ordre des images envoyé au contrôleur (par nom) :', orderedNames);

            const titre = document.getElementById('titre').value;
            const typeChoix = document.getElementById('typeChoix').value;
            const meuble = document.getElementById('meuble').value;
            const prix = document.getElementById('prix').value;
            const description = document.getElementById('description').value;
            const complementAdresse = document.getElementById('complementAdresse').value;
            const codePostal = document.getElementById('codePostal').value;
            const ville = document.getElementById('ville').value;
            const coordinates = await getCoordinates(ville);

            let latitude = null;
            let longitude = null;

            if (coordinates) {
                latitude = coordinates.lat;
                longitude = coordinates.lon;
                console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);

                const departement = document.getElementById('departement').value;
                const surface = document.getElementById('surface').value;
                const nombrePiece = document.getElementById('nombrePiece').value;
                const chambre = document.getElementById('chambre').value;
                const cuisine = document.getElementById('cuisine').value;
                const salleDeBain = document.getElementById('salleDeBain').value;
                const toilette = document.getElementById('toilette').value;
                const salon = document.getElementById('salon').value;
                const garage = document.getElementById('garage').value;
                const terrasse = document.getElementById('terrasse').value;
                const cave = document.getElementById('cave').value;
                const grenier = document.getElementById('grenier').value;
                const parkingChoix = document.getElementById('parkingChoix').value;
                const balcon = document.getElementById('balcon').value;
                const dateDPE = document.getElementById('dateDPE').value;
                const classeEnergetique = document.getElementById('classeEnergetique').value;
                fetch('../../controller/ajouterAnnonce.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `orderedNames=${JSON.stringify(orderedNames)}&titre=${titre}&typeChoix=${typeChoix}&prix=${prix}&meuble=${meuble}&description=${description}&complementAdresse=${complementAdresse}&codePostal=${codePostal}&ville=${ville}&departement=${departement}&surface=${surface}&nombrePiece=${nombrePiece}&chambre=${chambre}&cuisine=${cuisine}&salleDeBain=${salleDeBain}&toilette=${toilette}&salon=${salon}&garage=${garage}&terrasse=${terrasse}&cave=${cave}&grenier=${grenier}&parkingChoix=${parkingChoix}&balcon=${balcon}&dateDPE=${dateDPE}&classeEnergetique=${classeEnergetique}&latitude=${latitude}&longitude=${longitude}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        document.getElementById('erreur').innerHTML = data.message;
                    }
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    document.getElementById('erreur').innerHTML = "Une erreur est survenue lors de la connexion.";
                });
            } else {
                document.getElementById('erreur').innerHTML = "Erreur dans la récupération des coordonnées.";
            }
        });
    });
</script>