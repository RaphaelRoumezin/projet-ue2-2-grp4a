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
    <!-- Relation avec lucide icons -->
    <link rel="stylesheet" href="https://unpkg.com/lucide-static@latest/font/lucide.css" />
    <link rel="stylesheet" href="portfolio.css">
</head>

<body>

    <!-- Barre de navigation -->
    <nav class="cool-navbar">
        <a id="link-accueil" href="#">Accueil</a>
        <a id="link-projets" href="#projets">Mes projets</a>
        <a id="link-experiences" href="#experiences">Mes expériences</a>
        <a id="link-competences" href="#competences">Mes compétences</a>
        <a id="link-apropos" href="#apropos">A propos</a>
        <a href="contact.php?to=<?= $name ?>">Contact</a>
    </nav>

    <div class="navbar-spacer"></div>

    <!-- Section Accueil -->
    <section id="accueil" class="section hero">
        <img src="<?= $personne["photo"] ?>" alt="Ma photo" width="250" class="fade-in">
        <div class="text-block fade-in delayed">
            <h1>Bonjour, je suis <?= $personne["fullname"] ?></h1>
            <p><?= $personne["description"] ?></p>
            <a href="CV/CVsduchanaud.pdf">
            <button type="button" class="btn btn-outline-primary">
                <span class="icon-download"></span>
                Télécharger mon CV
            </button>
            </a>
        </div>
    </section>


    
    
    
    
    <!-- Section Projets -->
    <section id="projets" class="section">
        <h2>Mes Projets</h2>

        <div class="project-card fade-in paused">
            <div class="project-top">
                <img src="images/Projet1.png" alt="Projet 1" class="project-image">

                <div class="project-info">
                    <h3></span>Projet 1</h3>
                    <p class="project-tags">Introduction à la cybersécurité & sécurité ISR </p>
                </div>
                <div class="icon-network"></div>
            </div>

            <div class="project-description">
                <p>
                    Ceci est une description détaillée de mon projet.  
                    J'explique ici ce que j'ai fait, les technologies utilisées et l'objectif du projet.
                </p>
            </div>
        </div>

        <div class="project-card fade-in paused">
            <div class="project-top">
                <img src="images/Projet2.png" alt="Projet 2" class="project-image">

                <div class="project-info">
                    <h3>Projet 2</h3>
                    <p class="project-tags">Techniques d'administration & supervision ISR</p>
                </div>
                <div class="icon-network"></div>
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
                <img src="images/Projet3.png " alt="Projet 3" class="project-image">

                <div class="project-info">
                    <h3>Projet 3</h3>
                    <p class="project-tags">Programmation et DevSecOps</p>
                </div>
                <div class="icon-code-xml"></div>
            </div>

            <div class="project-description">
                <p>
                    Notre projet est donc la réalisation du site sur lequel vous êtes actuellement.
                    Vous avez seulement l'image d'accueil ici, mais vous pouvons donc tout observer par vous même.
                </p>
            </div>
        </div>
    </section>

    <!-- Section Mes expériences -->
    <section id="experiences" class="section">
        <h2>Mes expériences</h2>
        <p>Expériences professionnelles et personnelles</p>
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
        const pausedAnimations = document.querySelectorAll(".fade-in.paused");

        let currentSection = null;

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

        const offsets = {};
        sections.forEach(section => {
            offsets[section.id] = section.offsetTop;
        });
        // Mettre la section accueil comme la plus haute
        offsets["accueil"] = -1;

        function onScroll() {
            const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

            let newSection = currentSection;
            for (const [id, offset] of Object.entries(offsets)) {
                if (scrollPosition >= offset) {
                    newSection = id;
                }
            }

            if (newSection !== currentSection) {
                updateActiveLink(newSection);
            }
        }

        // Enregistrer l'événement de scroll et exécuter une fois au chargement
        window.addEventListener("scroll", onScroll);
        onScroll();

        navLinks.forEach(el => {
            el.addEventListener("click", (e) => {
                updateActiveLink(e.target.id.replace("link-", ""));
            });
        });

        // Créer un IntersectionObserver pour détecter quand les éléments entrent dans le viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove("paused");
                    observer.unobserve(entry.target); // Arrêter d'observer une fois l'animation jouée
                }
            });
        }, { threshold: 0.5 }); // Déclencher quand 10% de l'élément est visible

        pausedAnimations.forEach(el => {
            observer.observe(el);
        });
    </script>
</body>

</html>