<?php
    try {
        $db = new PDO('mysql:host=10.1.144.160;dbname=nosportfolios', "abcd", "abcd");
    } catch (Exception $e) {
        die('Impossible de se connecter à la DB : ' . $e->getMessage());
    }
?>