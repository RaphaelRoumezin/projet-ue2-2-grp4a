<?php
    include "../interne/db.php";

    session_start();

    // Vérification de la connexion
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo "Accès interdit.";
        exit();
    }

    $TYPES_AUTORISES = ["competence", "experience"];

    if (isset($_GET['type']) && in_array($_GET['type'], $TYPES_AUTORISES) && isset($_GET['id'])) {
        if ($_GET['type'] == 'experience') {
            // Récupérer le fichier image associé
            $query = $db->prepare("SELECT image FROM experience WHERE id = :id AND membre = :membre");
            $query->execute([
                'id' => $_GET['id'],
                'membre' => $_SESSION['user_id']
            ]);

            $experience = $query->fetch(PDO::FETCH_ASSOC);
            if ($experience && !empty($experience['image'])) {
                $imagePath = $experience['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Supprimer le fichier image
                }
            }
        }

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