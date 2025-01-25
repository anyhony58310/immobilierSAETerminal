<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Accueil</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="./style/index.css">
</head>

<script>
    function annonceLocation() {
        
    }
</script>

<body>
    <?php
        include './vue/navigation/navigation.php';
    ?>

    <main>
        <div id="select">
            <div id="boutonsChoix">
                <button id="locationButton" onclick="showDiv('location')">Location</button>
                <button id="achatButton" onclick="showDiv('achat')">Achat</button>
            </div>

            <div id="locationDiv" class="content">
                <br>
                <p>Vous recherchez une location ? C'est par ici !</p>
                <br>
                <button onclick="annonceLocation()" id="decouvrir">DECOUVRIR</button>
            </div>
            <div id="achatDiv" class="content">
                <br>
                <p>Vous cherchez à acheter ? C'est par ici !</p>
                <br>
                <button id="decouvrir">DECOUVRIR</button>
            </div>
        </div>

        <div id="details">
            <h2>LE SITE D'ANNONCE IMMOBILIERE QUI VOUS ACCOMPAGNE DANS VOTRE RECHERCHE DE LOGEMENTS</h2>
            <div id="explications">
                <div id="carte" class="img"></div>
                <div class="texte">
                    <h3>UNE RECHERCHE AVANCEE</h3>
                    <p class="explicationTexte">- Une carte interactive et en temps réel</p>
                    <p class="explicationTexte">- Des filtres pour rechercher rapidement</p>
                    <p class="explicationTexte">- Acheter un logement facilement</p>
                    <p class="explicationTexte">- Louer un logement facilement</p>
                </div>
            </div>
            <br>
            <div id="explications">
                <div id="client" class="img"></div>
                <div class="texte">
                    <h3>DEPOSER VOTRE ANNONCE</h3>
                    <p class="explicationTexte">- Déposez votre propre annonce</p>
                    <p class="explicationTexte">- Modifiez, créez, supprimez vos posts</p>
                    <p class="explicationTexte">- Tous le monde peut voir vos posts</p>
                    <p class="explicationTexte">- Déposez vos annonces, qui que vous soyez</p>
                </div>
            </div>
            <br>
            <div id="explications">
                <div id="organise" class="img"></div>
                <div class="texte">
                    <h3>UNE RECHERCHE AVANCEE</h3>
                    <p class="explicationTexte">- Likez et retrouver tous vos postes favoris</p>
                    <p class="explicationTexte">- Retrouvez toutes vos informations de comptes</p>
                    <p class="explicationTexte">- Visitez les quartiers via la carte interactive</p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p id="footer">Un site de Anthony | Nicolas | Mamourou</p>
    </footer>
</body>
</html>

<script>
        function showDiv(type) {
            const locationDiv = document.getElementById('locationDiv');
            const achatDiv = document.getElementById('achatDiv');
            const locationButton = document.getElementById('locationButton');
            const achatButton = document.getElementById('achatButton');

            if (type === 'location') {
                locationDiv.style.display = 'block';
                achatDiv.style.display = 'none';
                locationButton.classList.add('active');
                achatButton.classList.remove('active');
            } else if (type === 'achat') {
                locationDiv.style.display = 'none';
                achatDiv.style.display = 'block';
                achatButton.classList.add('active');
                locationButton.classList.remove('active');
            }
        }

        window.onload = function() {
            showDiv('location');
        };
</script>