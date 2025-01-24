<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 404 - Page introuvable</title>
    <link rel="stylesheet" href="error.css">
    <script>
        setTimeout(() => {
            window.location.href = "http://localhost/sitesae/index.php";
        }, 5000);
    </script>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Oups ! Page introuvable</h2>
        <p>La page que vous recherchez n'existe pas ou a été déplacée.</p>
        <p>Vous serez redirigé vers <a href="http://localhost/sitesae/index.php">l'accueil</a> dans quelques secondes.</p>
        <a href="http://localhost/sitesae/index.php" class="button">Retour à l'accueil</a>
    </div>
</body>
</html>
