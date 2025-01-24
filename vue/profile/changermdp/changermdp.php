<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Modifier le Profil</title>
        <link rel="stylesheet" href="./changermdp.css">
    </head>
    <body>
        <form id="mdpform" method="POST">
            <p id="erreur"></p>

            <label for="mdp">Nouveau MDP:</label>
            <input type="text" id="mdp" name="mdp" required>
            
            <label for="mdpDeux">Répéter MDP:</label>
            <input type="text" id="mdpDeux" name="mdpDeux" required>
            
            <div style="display: flex; justify-content: space-between;">
                <button type="submit" name="update">Enregistrer</button>
                <button type="button" onclick="window.location.href = './../profile.php';">Annuler</button>
            </div>
        </form>
    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('mdpform').addEventListener('submit', function(event) {
            event.preventDefault();

            const mdp = document.getElementById('mdp').value;
            const mdpDeux = document.getElementById('mdpDeux').value;

            fetch('./../../../controller/changermdp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `mdp=${encodeURIComponent(mdp)}&mdpDeux=${encodeURIComponent(mdpDeux)}`
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
                document.getElementById('erreur').innerHTML = "Erreur lors de l'envoie";
            });
        });
    });
</script>