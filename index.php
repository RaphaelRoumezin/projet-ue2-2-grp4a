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
            <a href="echaudat.html" class="lien-invisible">
                <div class="card">
                    <img src="images/echaudat.jpg" class="card-img-top" alt="Ethan">
                    <div class="card-body">
                        <h5 class="card-title">Ethan CHAUDAT</h5>
                        <p class="card-text">
                            En formation cybersécurité, je développe mes compétences en protection,
                            détection et réponse aux incidents.</p>
                        <div class="d-grid">
                            <button type="button" class="btn btn-secondary">Consulter le portfolio</button>
                        </div>
                    </div>
                </div>
            </a>

            <a href="sduchanaud.html" class="lien-invisible">
                <div class="card">
                    <img src="images/sduchanaud.jpg" class="card-img-top" alt="Simon">
                    <div class="card-body">
                        <h5 class="card-title">Simon DUCHANAUD</h5>
                        <p class="card-text">Étudiant en cybersécurité, passionné par la défense des systèmes et le
                            pentest.
                            Découvrez mes projets et mon parcours.</p>
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary">Consulter le portfolio</button>
                        </div>
                    </div>
                </div>
            </a>

            <a href="rroumezin.html" class="lien-invisible">
                <div class="card">
                    <img src="images/rroumezin.jpg" class="card-img-top" alt="Raphaël ROUMEZIN">
                    <div class="card-body">
                        <h5 class="card-title">Raphaël ROUMEZIN</h5>
                        <p class="card-text">Passionné par les métiers de la cybersécurité, je suis toujours à la
                            recherche
                            de nouvelles opportunités d'apprendre.</p>
                        <div class="d-grid">
                            <button type="button" class="btn btn-secondary">Consulter le portfolio</button>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </main>

    <footer>
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>