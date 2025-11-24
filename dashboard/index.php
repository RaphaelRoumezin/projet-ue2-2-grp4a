<?php
    include "../interne/db.php";

    $statuts = [];

    // Démarrage de la session
    session_start();

    // Vérification de la connexion
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        http_response_code(302);
        exit();
    }

    $query = $db->prepare("SELECT fullname FROM membre WHERE id = :id");
    $query->execute(['id' => $_SESSION['user_id']]);
    $userData = $query->fetch();

    if (!$userData) {
        // L'utilisateur n'existe pas, paradoxe
        header("Location: logout.php");
        http_response_code(400);
        exit();
    }

    if (isset($_POST['ajout_competence'])) {
        $nom = $_POST['nom'] ?? '';

        if ($nom == '') {
            $statuts[] = ["warning", "Le nom de la compétence à ajouter est obligatoire."];
        } else {
            // Insertion de la compétence
            $query = $db->prepare("INSERT INTO competence (membre, nom) VALUES (:membre, :nom)");
            $query->execute([
                'membre' => $_SESSION['user_id'],
                'nom' => $nom
            ]);

            $statuts[] = ["success", "Compétence ajoutée avec succès."];
        }
    }

    // Récupération des compétences
    $query = $db->prepare("SELECT * FROM competence WHERE membre = :id");
    $query->execute(['id' => $_SESSION['user_id']]);
    $competences = $query->fetchAll();
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
    <link rel="stylesheet" href="../index.css">

    <style>
        main {
            min-width: calc(100vw - 60px);
            margin-top: 20px;
        }

        h2 {
            margin-top: 30px;
        }

        .zone-ajout-competence {
            max-width: 600px;
        }

        a.lien-suppression {
            color: unset;
            text-decoration: none;
        }

        a.lien-suppression:hover {
            text-decoration: line-through;
        }
    </style>

    <title>Dashboard</title>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Dashboard</h1>
    </header>
    <main>
        Connecté en tant que <?= htmlspecialchars($userData['fullname']) ?>. <a href="logout.php">Se déconnecter</a>

        <div class="zone-statuts">
            <?php foreach ($statuts as [$type, $contenu]) : ?>
                <div class="alert alert-<?= $type ?>" role="alert">
                    <?= htmlspecialchars($contenu) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Mes compétences</h2>
        <?php if (count($competences) === 0) : ?>
            <p>Vous n'avez pas encore ajouté de compétence.</p>
        <?php else : ?>
            <ul>
                <?php foreach ($competences as $competence) : ?>
                    <li>
                        <a class="lien-suppression" href="suppression.php?type=competence&id=<?= $competence['id'] ?>"><?= htmlspecialchars($competence['nom']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <div class="input-group zone-ajout-competence">

                <input type="hidden" name="ajout_competence" value="1">
                <input name="nom" type="text" class="form-control" placeholder="Cybersécurité" required>
                <input class="btn btn-outline-primary" type="submit" value="Ajouter une compétence">
            </div>
        </form>
    </main>
    <!-- footer en bas de la page grace a notre liaison a index.css -->
    <footer>
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>