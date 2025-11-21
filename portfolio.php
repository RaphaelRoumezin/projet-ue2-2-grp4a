<?php
    include "interne/db.php";

    $name = $_GET['name'] ?? '';
    if ($name == '') {
        // Nom invalide, redirection vers la page principale
        header("Location: index.php");
        http_response_code(303);
        exit();
    }

    $query = $db->prepare("SELECT fullname, description, photo FROM membre WHERE name = :name LIMIT 1");
    if (!$query->execute(['name' => $name])) {
        die("Erreur lors de la requête SQL : " . implode(", ", $query->errorInfo()));
    }

    $personne = $query->fetch(PDO::FETCH_ASSOC);
    if (!$personne) {
        // Personne non trouvée, redirection vers la page principale
        header("Location: index.php");
        http_response_code(303);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio de <?= $personne["fullname"] ?></title>
    <!-- Relation avec bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="portfolio.css">
</head>

<body>

    <!-- Barre de navigation -->
    <nav class="cool-navbar">
        <ul>
            <li><a id="link-accueil" class="active" href="#">Accueil</a></li>
            <li><a id="link-projets" href="#projets">Mes projets</a></li>
            <li><a id="link-competences" href="#competences">Mes compétences</a></li>
            <li><a id="link-apropos" href="#apropos">A propos</a></li>
            <li><a href="contact.php?to=<?= $name ?>">Contact</a></li>
        </ul>
    </nav>

    <div class="navbar-spacer"></div>

    <!-- Section Accueil -->
    <section id="accueil" class="section hero">
        <img src="<?= $personne["photo"] ?>" alt="Ma photo" width="250">
        <div>
            <h1>Bonjour, je suis <?= $personne["fullname"] ?></h1>
            <p><?= $personne["description"] ?></p>
        </div>
    </section>

    <!-- Section Projets -->
    <section id="projets" class="section">
        <h2>Mes Projets</h2>

        <div class="project-card">
        <div class="project-top">
            <img src="images/Projet1.png" alt="Projet 1" class="project-image">

            <div class="project-info">
                <h3>Projet ISR 1</h3>
                <p class="project-tags">Introduction à la cybersécurité & sécurité ISR </p>
            </div>
        </div>

        <div class="project-description">
            <p>
                Ceci est une description détaillée de mon projet.  
                J'explique ici ce que j'ai fait, les technologies utilisées et l'objectif du projet.
            </p>
        </div>
    </div>
        <div class="project-card">
        <div class="project-top">
            <img src="images/Projet2.png " alt="Projet 2" class="project-image">

            <div class="project-info">
                <h3>Projet ISR 2</h3>
                <p class="project-tags">Techniques d'administration & supervision ISR</p>
            </div>
        </div>

        <div class="project-description">
            <p>
                Ceci est une description détaillée de mon projet.  
                J'explique ici ce que j'ai fait, les technologies utilisées et l'objectif du projet.
            </p>
        </div>
    </div>
    </section>



    <!-- Section Mes compétences -->
    <section id="competences" class="section">
        <h2>Mes compétences</h2>
        <p>Voici la liste de mes compétences acquises au cours de ma vie de GOAT...</p>
    </section>

    <!-- Section À propos -->
    <section id="apropos" class="section">
        <h2>À propos</h2>
        <p>Ta présentation, ton parcours, tes passions...</p>
    </section>




    <script>
        const sections = document.querySelectorAll("section");
        const navLinks = document.querySelectorAll(".cool-navbar a");

        let currentSection = "accueil";

        function updateActiveLink(id) {
            // Enlever l'ancienne classe active
                if (currentSection) {
                    const oldLink = document.querySelector(`#link-${currentSection}`);
                    if (oldLink) {
                        oldLink.classList.remove("active");
                    }
                }

                currentSection = id;

                const activeLink = document.querySelector(`#link-${id}`);
                if (activeLink) {
                    activeLink.classList.add("active");
                }
        }

        const observer = new IntersectionObserver((entries) => {
            // Garder la dernière section visible
            entries.filter(entry => entry.isIntersecting);
            const entry = entries[entries.length - 1];

            if (entry.isIntersecting) {
                const id = entry.target.id;

                // Éviter les mises à jour redondantes
                if (currentSection === id) return;

                updateActiveLink(id);
            }
        }, {
            root: null,
            threshold: 0.99,
        });


        sections.forEach(section => observer.observe(section));

        navLinks.forEach(el => {
            el.addEventListener("click", (e) => {
                updateActiveLink(e.target.id.replace("link-", ""));
            });
        });
    </script>
</body>

</html>