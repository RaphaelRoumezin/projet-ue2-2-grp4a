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
                    <div class="card card-personne">
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
        
        <div class="card card-longue">
        <link rel="stylesheet" href="index.css">
            <h5 class="card-header">Qui sommes nous ?</h5>
            <div class="card-body">
                <h5 class="card-title">Notre projet </h5>
                <p class="card-text">Nous sommes trois étudiants en première année de Bachelor Cybersécurité au sein de la 
                    <img class="images-text" src="images/guardialogo.jfif" alt="guardialogo"> <span class="cyan-gras">Guardia Cybersecurity School</span>, sur le campus de Lyon. Animés par un intérêt commun pour le domaine de la sécurité informatique et par la volonté de développer nos compétences techniques, nous avons entrepris la réalisation de ce portfolio dans le cadre de notre projet DevSecOps. <br> <br>
                Ce travail a pour objectif de présenter de manière claire et structurée l’ensemble des connaissances et des compétences que nous avons acquises depuis le début de notre formation. Vous y trouverez, entre autres, une description de nos parcours individuels, les technologies que nous maîtrisons, ainsi que les projets que nous avons développés au cours de ce semestre. <br> <br>
                Au-delà de l'aspect technique, ce portfolio reflète également notre démarche d’apprentissage : approche collaborative, mise en pratique des notions vues en cours, adoption de bonnes pratiques DevSecOps, gestion de version, automatisation et documentation rigoureuse. Il met en lumière non seulement nos réalisations, mais aussi notre capacité à travailler en équipe et à mener à bien un projet complet, de la conception à la mise en production. <br> <br>
                En partageant ce portfolio, nous souhaitons démontrer notre motivation, notre engagement et notre progression dans le domaine de la cybersécurité, tout en offrant une vision transparente de notre travail et de notre évolution au sein de la
                <img class="images-text" src="images/guardialogo.jfif" alt="guardialogo"> <span class="cyan-gras"> Guardia Cybersecurity School.</span></p>
                <a href="https://guardia.school/" class="btn btn-primary">Qu'est ce que Guardia Cybersecurity School ?</a>
            </div>
        </div>
    </main>


    <footer>
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>