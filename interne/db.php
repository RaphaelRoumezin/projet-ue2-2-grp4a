<?php
    include_once 'env.php';
    // Connexion à la base de données (si n'a pas déjà été fait)
    if (!isset($db)) {
        try {
            $db = new PDO(get_env_value("DB_URL"), get_env_value("DB_USER"), get_env_value("DB_PASSWORD"));
        } catch (Exception $e) {
            die('Impossible de se connecter à la DB : ' . $e->getMessage());
        }
    }
?>