<style>
    .navigation {
        height: 125px;
        background-color: #007198;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1vh;
    }

    .logo {
        height: 125px;
        width: auto;
    }

    .boutons-container {
        display: flex;
        gap: 20px;
    }

    .boutons {
        height: 32px;
    }

    .boutons:hover {
        transform: scale(1.2);
        transition: 0.5s;
    }

    a {
        display: inline-block;
        text-decoration: none;
    }

    .ajouter {
        background-color: #04BBFF;
        border: none;
        padding: 10px;
        height: 50px;
        border-radius: 14.5px;
        color: white;
        font-size: 20px;
        cursor: pointer;
    }

    .ajouter:hover {
        transform: scale(1.1);
        transition: 0.5s;
    }

    #bienvenue {
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
</style>

<script>
    function ajouterAnnonce() {
        window.location.href="http://localhost/sitesae/vue/ajouterAnnonce/ajouterAnnonce.php";
    }
</script>

<header>
    <div class="navigation">
        <img class="logo" src="http://localhost/sitesae/style/lecoin.png" alt="logo">
        <button onclick="ajouterAnnonce()" class="ajouter">AJOUTER</button>
        <div class="boutons-container">
            <a href="http://localhost/sitesae/vue/like/like.php">
                <img class="boutons" src="http://localhost/sitesae/style/like.png" alt="like">
            </a>
            <a href="http://localhost/sitesae/vue/mesannonces/mesannonces.php">
                <img class="boutons" src="http://localhost/sitesae/style/annonce.png" alt="mesannonces">
            </a>
            <a href="http://localhost/sitesae/vue/profile/profile.php">
                <img class="boutons" src="http://localhost/sitesae/style/connexion.png" alt="connexion">
            </a>
        </div>
    </div>
</header>

