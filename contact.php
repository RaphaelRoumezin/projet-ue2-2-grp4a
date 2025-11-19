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

    <style>
        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(5, 1fr);
            grid-column-gap: 0px;
            grid-row-gap: 0px;
        }

        .zone-nom { grid-area: 1 / 1 / 2 / 2; }
        .zone-prenom { grid-area: 1 / 2 / 2 / 3; }
    </style>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Contacter Steve Harvey</h1>
    </header>

    <main class="section-etroite">
        <form method="post">
            <div class="zone-nom">
                <label for="input-nom" class="form-label">Nom* :</label>
                <input type="text" class="form-control" id="input-nom" placeholder="Dupont" required>
            </div>
            <div class="zone-prenom">
                <label for="input-prenom" class="form-label">Prénom* :</label>
                <input type="text" class="form-control" id="input-prenom" placeholder="Jean" required>
            </div>
        </form>
    </main>

    <footer>
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>