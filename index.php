<?php
    include "interne/db.php";

    $query = $db->prepare("SELECT name, fullname, intro, photo, bs_color FROM membre");
    if (!$query->execute()) {
        die("Erreur lors de la requête SQL : " . implode(", ", $query->errorInfo()));
    }

    $membres = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="index.css">
    <title>Nos portfolios</title>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Nos portfolios</h1>
    </header>

    <main>
        <!-- Mise en place de "cartes" pour chacun des participants du groupe/ image, nom prénom, description, bouton -->
        <div class="cards">
            <?php foreach ($membres as $membre): ?>
                <a href="portfolio.php?name=<?= htmlspecialchars($membre['name']) ?>" class="lien-invisible">
                    <div class="card">
                        <img src="<?= htmlspecialchars($membre['photo']) ?>" class="card-img-top"
                            alt="Photo de <?= htmlspecialchars($membre['fullname']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($membre['fullname']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($membre['intro']) ?></p>
                            <div class="d-grid">
                                <button type="button" class="btn btn-<?= htmlspecialchars($membre['bs_color']) ?>">Consulter le portfolio</button>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>