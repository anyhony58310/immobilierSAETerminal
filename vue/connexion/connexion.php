<?php
    session_start();
?>

<script>
    function accueil() {
        window.location.href = "http://localhost/sitesae/index.php";
    }

    function inscription() {
        window.location.href = "http://localhost/sitesae/vue/inscription/inscription.php";
    }
</script>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire d'Inscription</title>
  <link rel="stylesheet" href="./connexion.css">
</head>
<body>
  <div class="form-container">
    <div class="form">
        <h2>
            <?php
                if (isset($_SESSION['connexion']) && !empty($_SESSION['connexion'])) {
                    echo htmlspecialchars($_SESSION['connexion']);
                    $_SESSION['connexion'] = "";
                } else {
                    echo "Connectez-vous";
                }
            ?>
        </h2>
      <form id="loginForm" method="post">
        <p id="erreur"></p>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

        <button type="submit">Connexion</button>
      </form>
    </div>
    <div class="image">
        <div class="boutonNav">
            <button id="accueil" onclick="accueil()">ACCUEIL</button>
            <button id="connexion" onclick="inscription()">Je n'ai pas de compte</button>
        </div>
    </div>
  </div>
</body>
</html>

<script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const email = document.getElementById('email').value;
                const mdp = document.getElementById('password').value;

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