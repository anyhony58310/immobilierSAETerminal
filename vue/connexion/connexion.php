<?php
    session_start();
?>
<script>
    function accueil() {
        window.location.href="http://localhost/sitesae/index.php";
    }

    function inscription() {
        window.location.href="http://localhost/sitesae/vue/inscription/inscription.php";
    }
</script>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="./connexion.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="containFormulaire">
        <div id="img">
            <h1>CONNEXION</h1>

            <button onclick="inscription()" id="inscription">Vous n'avez pas de compte ?</button>

            <button id="accueil" onclick="accueil()" >ACCUEIL</button>
        </div>
        <div id="formulaire">
            <form id="loginForm" method="POST">
                <p id="result"></p>
                <label for="email">Adresse mail</label>
                <input type="email" id="email" name="email" required>
                <br><br>
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required>
                <br><br>
                <button type="submit" id="boutonConnexion">CONNEXION</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const email = document.getElementById('email').value;
                const mdp = document.getElementById('mdp').value;

                fetch('../../controller/loginController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `email=${encodeURIComponent(email)}&mdp=${encodeURIComponent(mdp)}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        document.getElementById('result').innerHTML = data.message;
                    }
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    document.getElementById('result').innerHTML = "Une erreur est survenue lors de la connexion.";
                });
            });
        });
    </script>
</body>
</html>