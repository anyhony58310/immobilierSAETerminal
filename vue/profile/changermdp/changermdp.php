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
        });
    });
</script>