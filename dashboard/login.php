<?php
    include "../interne/db.php";

    $statuts = [];

    // Démarrage de la session
    session_start();

    // Reception de formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;

        // Vérification des données reçues
        $user = $_POST['user'] ?? '';
        if ($user == '') {
            $valide = false;
            $statuts[] = ["warning", "Le nom d'utilisateur est obligatoire."];
        }

        $pass = $_POST['pass'] ?? '';
        if ($pass == '') {
            $valide = false;
            $statuts[] = ["warning", "Le mot de passe est obligatoire."];
        }
        if ($valide == true) {
            // Récupération du hash du mot de passe
            $query = $db->prepare("SELECT id, password, 2fa FROM membre WHERE name = :name");
            $query->execute(['name' => $user]);
            $userData = $query->fetch();

            if (!$userData || !password_verify($pass, $userData['password'])) {
                $statuts[] = ["danger", "Nom d'utilisateur ou mot de passe incorrect."];
            } else {
                // Mot de passe correct, connexion valide

                // Vérification du 2FA
                if (!is_null($userData['2fa'])) {
                    $_SESSION['pending_2fa_user_id'] = $userData['id'];
                    list($twofactortype, $twofactordata) = explode(":", $userData['2fa'], 2);

                    switch ($twofactortype) {
                        case 'discord':
                            // Envoi du code via Discord
                            include '../interne/discord.php';
                            $code = rand(100000, 999999);

                            $embeds = [
                                [
                                    "title" => "Code de vérification Nos Portfolios",
                                    "description" => "Votre code de vérification est : **{$code}**",
                                    "color" => 16638692,
                                    "footer" => [
                                        "text" => "Ce code est strictement personnel. Ne le partagez avec personne."
                                    ]
                                ]
                            ];

                            // Création du canal DM (s'il n'existe pas) et envoi du message
                            $dmChannel = discord_create_dm_channel($twofactordata);
                            discord_send_message_with_embed($dmChannel['id'], "", $embeds);

                            $_SESSION['pending_2fa_code'] = "static:" . $code;
                            break;

                        case 'totp':
                            // Rien à faire, le code sera généré par l'application TOTP
                            $_SESSION['pending_2fa_code'] = "totp:" . $twofactordata;
                            break;
                        
                        default:
                            die("Méthode 2FA retournée par la base de donnée inconnue.");
                            break;
                    }

                    header("Location: otp.php");
                    http_response_code(302);
                    exit();
                }

                // Connexion réussie
                $_SESSION['user_id'] = $userData['id'];

                $statuts[] = ["success", "Connexion réussie. Redirection en cours..."];
            }
        }
    }

    // Vérification de la connexion
    if (isset($_SESSION['user_id'])) {
        header("Location: .");
        http_response_code(302);
        exit();
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
    <link rel='icon' href='../favicon.png'>
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
        <h1 class="titre">Connexion au dashboard</h1>
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
            <div class="zone-user">
                <label for="input-user" class="form-label">Nom d'utilisateur<span class="text-danger">*</span>:</label>
                <input name="user" type="text" class="form-control" id="input-user" placeholder="echaudat" required value="<?= htmlspecialchars($user ?? '') ?>">
            </div>
            <div class="zone-pass">
                <label for="input-pass" class="form-label">Mot de passe<span class="text-danger">*</span>:</label>
                <input name="pass" type="password" class="form-control" id="input-pass" placeholder="jaimelescroissants69" required value="<?= htmlspecialchars($pass ?? '') ?>">
            </div>
            <div class="zone-boutons">
                <input class="btn btn-outline-secondary" type="reset" value="Réinitialiser">
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