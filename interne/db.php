<?php
    try {
        $db = new PDO('mysql:host=localhost;dbname=nosportfolios', "root");
    } catch (Exception $e) {
        die('Impossible de se connecter à la DB : ' . $e->getMessage());
    }
?>