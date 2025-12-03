<?php
    // Connexion à la base de données (si n'a pas déjà été fait)
    if (!isset($db)) {
        try {
            $db = new PDO('mysql:host=10.1.144.160;dbname=nosportfolios', "abcd", "abcd");
        } catch (Exception $e) {
            die('Impossible de se connecter à la DB : ' . $e->getMessage());
        }
    }
?>