<?php
    include "interne/db.php";

    $statuts = [];

    // Récupération du destinataire
    $name = $_GET['to'] ?? $_POST['to'] ?? '';
    if ($name == '') {
        // Destinataire non spécifié, redirection vers la page principale
        header("Location: index.php");
        http_response_code(303);
        exit();
    }

    $query = $db->prepare("SELECT id, fullname FROM membre WHERE name = :name LIMIT 1");
    if (!$query->execute(['name' => $name])) {
        die("Erreur lors de la requête SQL : " . implode(", ", $query->errorInfo()));
    }

    $to = $query->fetch(PDO::FETCH_ASSOC);
    if (!$to) {
        // Destinataire non trouvé, redirection vers la page principale
        header("Location: index.php");
        http_response_code(303);
        exit();
    }

    // Reception de formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;

        // Vérification des données reçues
        $nom = $_POST['nom'] ?? '';
        if ($nom == '') {
            $valide = false;
            $statuts[] = ["danger", "Le nom est obligatoire."];
        }

        $prenom = $_POST['prenom'] ?? '';
        if ($prenom == '') {
            $valide = false;
            $statuts[] = ["danger", "Le prénom est obligatoire."];
        }

        $genre = $_POST['genre'] ?? 'n';
        if (!in_array($genre, ['n', 'h', 'f', 'a', 'c'])) {
            $valide = false;
            $statuts[] = ["danger", "Le genre sélectionné n'est pas valide."];
        }

        $tel = $_POST['tel'] ?? '';
        if ($tel == '') {
            $valide = false;
            $statuts[] = ["danger", "Le numéro de télépone est obligatoire."];
        } else {
            $tel = preg_replace("/[^0-9]/", '', $tel); // Ne garder que les numéros, supprimer espaces, tirets, etc.
            if (preg_match("/^0?[1-9][0-9]{8}$/", $tel) !== 1) { // Vérifier que le numéro est valide (9 chiffres, peut commencer par 0)
                $valide = false;
                $statuts[] = ["danger", "Le numéro de télépone est invalide. (doit suivre le format français)"];
            }
        }

        $email = $_POST['email'] ?? '';
        if ($email == '') {
            $valide = false;
            $statuts[] = ["danger", "L'email est obligatoire."];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valide = false;
            $statuts[] = ["danger", "L'email est invalide."];
        }

        $sujet = $_POST['subject'] ?? '';
        if ($sujet == '') {
            $valide = false;
            $statuts[] = ["danger", "Le sujet est obligatoire."];
        }

        $message = $_POST['message'] ?? '';
        if ($message == '') {
            $valide = false;
            $statuts[] = ["danger", "Le message est obligatoire."];
        }

        if ($valide == true) {
            // Insertion en base de données
            $query = $db->prepare("INSERT INTO message (destinataire, nom, prenom, genre, tel, email, sujet, content) VALUES (:destinataire, :nom, :prenom, :genre, :tel, :email, :sujet, :content)");
            $success = $query->execute([
                'destinataire' => $to['id'],
                'nom' => $nom,
                'prenom' => $prenom,
                'genre' => $genre,
                'tel' => $tel,
                'email' => $email,
                'sujet' => $sujet,
                'content' => $message
            ]);

            if (!$success) {
                die("Erreur lors de la requête SQL : " . implode(", ", $query->errorInfo()));
            }

            $statuts[] = ["success", "Votre message a été envoyé avec succès à ".$to["fullname"]." !"];
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
    <link rel="stylesheet" href="css/index.css">
    <link rel='icon' href='favicon.png'>
    <title>Nos portfolios</title>
    <!-- CSS directement dans notre contact, mise en place d'une "grille" -->
    <style>
        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: 15px;
            grid-row-gap: 10px;
        }

        .zone-statuts { grid-area: 1 / 1 / 2 / 3; }
        .zone-email { grid-area: 4 / 1 / 5 / 3; }
        .zone-subject { grid-area: 5 / 1 / 6 / 3; }
        .zone-message { grid-area: 6 / 1 / 7 / 3; }
        .zone-boutons {
            grid-area: 7 / 2 / 8 / 3;
            text-align: right;
        }
    </style>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Contacter <?= $to["fullname"] ?></h1>
    </header>
    <!-- rubriques nom prénom genres téléphone email sujet message -->
    <main class="section-etroite">
        <form method="post">
            <input type="hidden" name="to" value="<?= htmlspecialchars($name) ?>">

            <div class="zone-statuts">
                <?php foreach ($statuts as [$type, $contenu]) : ?>
                    <div class="alert alert-<?= $type ?>" role="alert">
                        <?= htmlspecialchars($contenu) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="zone-nom">
                <label for="input-nom" class="form-label">Nom<span class="text-danger">*</span>:</label>
                <input name="nom" type="text" class="form-control" id="input-nom" placeholder="Dupont" required value="<?= htmlspecialchars($nom ?? '') ?>">
            </div>
            <div class="zone-prenom">
                <label for="input-prenom" class="form-label">Prénom<span class="text-danger">*</span>:</label>
                <input name="prenom" type="text" class="form-control" id="input-prenom" placeholder="Jean" required value="<?= htmlspecialchars($prenom ?? '') ?>">
            </div>
            <div class="zone-genre">
                <label for="input-genre" class="form-label">Genre:</label>
                <select name="genre" id="input-genre" class="form-select">
                    <option value="n" <?php if (($genre ?? 'n') == 'n') echo 'selected'; ?>></option>
                    <option value="h" <?php if (($genre ?? 'n') == 'h') echo 'selected'; ?>>Homme</option>
                    <option value="f" <?php if (($genre ?? 'n') == 'f') echo 'selected'; ?>>Femme</option>
                    <option value="a" <?php if (($genre ?? 'n') == 'a') echo 'selected'; ?>>Autre</option>
                    <option value="c" <?php if (($genre ?? 'n') == 'c') echo 'selected'; ?>>Croissant</option>
                </select>
            </div>
            <div class="zone-tel">
                <label for="input-tel" class="form-label">Téléphone<span class="text-danger">*</span>:</label>
                <div class="input-group">
                    <span class="input-group-text" >+33</span>
                    <input name="tel" type="tel" class="form-control" id="input-tel" placeholder="X XX XX XX XX" required value="<?= htmlspecialchars($tel ?? '') ?>">
                </div>
            </div>
            <div class="zone-email">
                <label for="input-email" class="form-label">Email<span class="text-danger">*</span>:</label>
                <input name="email" type="email" class="form-control" id="input-email" placeholder="jeandupont@gmail.com" required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="zone-subject">
                <label for="input-subject" class="form-label">Sujet<span class="text-danger">*</span>:</label>
                <input name="subject" type="text" class="form-control" id="input-subject" required value="<?= htmlspecialchars($sujet ?? '') ?>">
            </div>
            <div class="zone-message">
                <label for="input-message" class="form-label">Message<span class="text-danger">*</span>:</label>
                <textarea name="message" name="message" id="input-message" class="form-control" rows="6" ><?= htmlspecialchars($message ?? '') ?></textarea>
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
        <script src="js/nuitjour.js"></script>
        -
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>