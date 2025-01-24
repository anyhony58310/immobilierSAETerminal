<?php
    session_start();
    $user = $_SESSION['infosUser'];
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Modifier le Profil</title>
        <link rel="stylesheet" href="./modifierProfile.css">
    </head>
    <body>
        <form id="changerProfile" method="POST">
            <p id="erreur"></p>

            <label for="pseudo">Pseudo:</label>
            <input type="text" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($user[0]['pseudo']); ?>" required>

            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user[0]['nom']); ?>" required>

            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user[0]['prenom']); ?>" required>

            <label for="telephone">Téléphone:</label>
            <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user[0]['tel']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user[0]['mail']); ?>" required>

            <div style="display: flex; justify-content: space-between;">
                <button type="submit" name="update">Modifier Profil</button>
                <button type="button" onclick="window.location.href = './../profile.php';">Annuler</button>
            </div>
        </form>
    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('changerProfile').addEventListener('submit', function(event) {
            event.preventDefault();

            const pseudo = document.getElementById('pseudo').value;
            const nom = document.getElementById('nom').value;
            const prenom = document.getElementById('prenom').value;
            const telephone = document.getElementById('telephone').value;
            const email = document.getElementById('email').value;

            fetch('./../../../controller/modifierProfile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `pseudo=${encodeURIComponent(pseudo)}&nom=${encodeURIComponent(nom)}&prenom=${encodeURIComponent(prenom)}&telephone=${encodeURIComponent(telephone)}&email=${encodeURIComponent(email)}`
            })
            .then(response => {
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error("Réponse invalide, format JSON attendu");
                    }
                });
            })
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
                document.getElementById('erreur').innerHTML = "Erreur lors de l'envoi ou de la réponse.";
            });
        });
    });
</script>