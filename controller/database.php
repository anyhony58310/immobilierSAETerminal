<?php
    class DatabaseConnexion {
        private $host = 'localhost';   // Hôte MySQL, généralement 'localhost' sous Uwamp
        private $db_name = 'immobilier';  // Nom de ta base de données
        private $username = 'root';    // Nom d'utilisateur MySQL, par défaut 'root' sous Uwamp
        private $password = 'root';        // Pas de mot de passe par défaut sous Uwamp
        public $conn;

        // Fonction pour établir la connexion
        public function connect() {
            $this->conn = null;

            try {
                // Créer une nouvelle instance PDO
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                // Définir le mode d'erreur de PDO sur Exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exception) {
                echo "Erreur de connexion : " . $exception->getMessage();
            }

            return $this->conn;
        }
    }
?>