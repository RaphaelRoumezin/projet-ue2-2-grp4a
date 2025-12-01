<?php
    include "../interne/db.php";

    $statuts = [];

    // Démarrage de la session
    session_start();

    if (!isset($_SESSION['pending_2fa_user_id']) || !isset($_SESSION['pending_2fa_code'])) {
        die("Aucune tentative de connexion 2FA en cours.");
    }

    // Reception de formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;

        // Vérification des données reçues
        $otp = $_POST['otp'] ?? '';
        if ($otp == '') {
            $valide = false;
            $statuts[] = ["warning", "Le code est obligatoire."];
        }

        if ($valide == true) {
            // Vérification du code 2FA
            list($expected_type, $expected_data) = explode(":", $_SESSION['pending_2fa_code'], 2);

            $valide = false;
            switch ($expected_type) {
                case 'static':
                    if ($otp === $expected_data) {
                        $valide = true;
                    }
                    break;

                default:
                    die("Méthode 2FA inconnue.");
            }

            if ($valide) {
                // Code valide, connexion de l'utilisateur
                $_SESSION['user_id'] = $_SESSION['pending_2fa_user_id'];

                // Nettoyage des variables temporaires
                unset($_SESSION['pending_2fa_user_id']);
                unset($_SESSION['pending_2fa_code']);

                header("Location: .");
                http_response_code(302);
                exit();
            } else {
                $statuts[] = ["danger", "Code à usage unique incorrect."];
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Relation avec bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Relation avec notre css -->
    <link rel="stylesheet" href="../css/index.css">
    <title>Connexion au dashboard</title>
    <!-- CSS directement dans notre page de login, mise en place d'une "grille" -->
    <style>
        form {
            display: grid;
            grid-template-columns: 1fr;
            grid-column-gap: 15px;
            grid-row-gap: 10px;
        }

        .zone-boutons {
            text-align: right;
        }
    </style>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Authentification à deux facteurs</h1>
    </header>
    <main class="section-etroite">
        <form method="post">
            <div class="zone-statuts">
                <?php foreach ($statuts as [$type, $contenu]) : ?>
                    <div class="alert alert-<?= $type ?>" role="alert">
                        <?= htmlspecialchars($contenu) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="zone-otp">
                <label for="input-otp" class="form-label">Code à usage unique<span class="text-danger">*</span>:</label>
                <input name="otp" type="text" class="form-control" id="input-otp" placeholder="XXXXXX" required>
            </div>
            <div class="zone-boutons">
                <button class="btn btn-primary" type="submit">Envoyer</button>
            </div>
        </form>
    </main>
    <!-- footer en bas de la page grace a notre liaison a index.css -->
    <footer>
        <a href="#" id="nuitjour"></a>
        <script src="../js/nuitjour.js"></script>
        -
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>