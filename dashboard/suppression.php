<?php
    include "../interne/db.php";

    session_start();

    // Vérification de la connexion
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo "Accès interdit.";
        exit();
    }

    $TYPES_AUTORISES = ["competence"];

    if (isset($_GET['type']) && in_array($_GET['type'], $TYPES_AUTORISES) && isset($_GET['id'])) {
        $query = $db->prepare("DELETE FROM ".$_GET['type']." WHERE id = :id AND membre = :membre");
        $query->execute([
            'id' => $_GET['id'],
            'membre' => $_SESSION['user_id']
        ]);

        header("Location: index.php");
        http_response_code(302);
        exit();
    } else {
        http_response_code(400);
        echo "Requête invalide.";
        exit();
    }
?>