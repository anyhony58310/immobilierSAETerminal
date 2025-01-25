<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        function accueil() {
            window.location.href = "http://localhost/sitesae/index.php";
        }

        function connexion() {
            window.location.href="http://localhost/sitesae/vue/connexion/connexion.php";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <link rel="stylesheet" href="./inscription.css">
</head>
<body>
  <div class="form-container">
    <div class="form">
      <h2>Inscrivez-vous</h2>
      <form id="inscriptionForm" method="post">
        <p id="erreur"></p>
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>

        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email" required>

        <label for="tel">Tel</label>
        <input type="number" id="tel" name="tel" placeholder="Entrez votre numéro de tel" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

        <button type="submit">S'inscrire</button>
      </form>
    </div>
    <div class="image">
        <div class="boutonNav">
            <button id="accueil" onclick="accueil()">ACCUEIL</button>
            <button id="connexion" onclick="connexion()">J'ai déjà un compte</button>
        </div>
    </div>
  </div>
</body>
</html>

<script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('inscriptionForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const nom = document.getElementById('nom').value;
                const prenom = document.getElementById('prenom').value;
                const pseudo = document.getElementById('pseudo').value;
                const tel = document.getElementById('tel').value;
                const email = document.getElementById('email').value;
                const mdp = document.getElementById('password').value;

                fetch('../../controller/inscriptionController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `nom=${encodeURIComponent(nom)}&prenom=${encodeURIComponent(prenom)}&pseudo=${pseudo}&tel=${encodeURIComponent(tel)}&email=${encodeURIComponent(email)}&mdp=${encodeURIComponent(mdp)}`
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
            });
        });
    </script>