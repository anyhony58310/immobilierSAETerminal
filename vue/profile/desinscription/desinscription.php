<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Modifier le Profil</title>
        <link rel="stylesheet" href="./desinscription.css">
    </head>
    <body>
        <form id="desinscriptionUtilisateur" method="POST">
            <p id="erreur"></p>

            <label for="supprimer">Entrer:</label>
            <input type="text" id="supprimer" name="supprimer" placeholder="supprimer" required>
            
            <label for="supprimerDeux">Répéter:</label>
            <input type="text" id="supprimerDeux" name="supprimerDeux" placeholder="supprimer" required>
            
            <div style="display: flex; justify-content: space-between;">
                <button type="submit" name="update">Désinscription</button>
                <button type="button" onclick="window.location.href = './../profile.php';">Annuler</button>
            </div>
        </form>
    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('desinscriptionUtilisateur').addEventListener('submit', function(event) {
            event.preventDefault();

            const supprimer = document.getElementById('supprimer').value;
            const supprimerDeux = document.getElementById('supprimerDeux').value;

            fetch('./../../../controller/desinscription.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `supprimer=${encodeURIComponent(supprimer)}&supprimerDeux=${encodeURIComponent(supprimerDeux)}`
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