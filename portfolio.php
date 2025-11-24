<?php
    include "interne/db.php";

    $name = $_GET['name'] ?? '';
    if ($name == '') {
        // Nom invalide, redirection vers la page principale
        header("Location: index.php");
        http_response_code(303);
        exit();
    }

    $query = $db->prepare("SELECT id, fullname, description, photo FROM membre WHERE name = :name LIMIT 1");
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

    $stmt = $db->prepare("SELECT * FROM reseau_social WHERE membre = ?");
    $stmt->execute([$personne['id']]);

    $reseaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="contact.php?to=<?= $name ?>">Contact</a>
    </nav>

    <div class="navbar-spacer"></div>

    <!-- Section Accueil -->
    <section id="accueil" class="section hero">
        <img src="<?= $personne["photo"] ?>" alt="Ma photo" width="250" class="fade-in">
        <div class="text-block fade-in delayed">
            <h1>Bonjour, je suis <?= $personne["fullname"] ?></h1>
            <p><?= $personne["description"] ?></p>
            
                <div class="buttons-colonne">
                    <a href="CV/<?= $name ?>.pdf" target="_blank" class="btn btn-outline-primary">
                        <span class="icon-download"></span>
                        Télécharger mon CV
                    </a>
                    <?php foreach ($reseaux as $reseau): ?>
                    <a href="<?= $reseau['url']?>" target="_blank" class="btn btn-outline-secondary">
                        <span class="icon-<?= $reseau['icone'] ?>"></span>
                        <?= $reseau['nom']?>
                    </a>
                    <?php endforeach; ?>
                </div>
            
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
                <div class="icon-network icon"></div>
            </div>

            <div class="project-description">
                <p>
                    Dans le cadre de ce projet, j'ai <strong>conçu</strong> et <strong>déployé</strong> une architecture réseau d'entreprise segmentée afin de renforcer la 
                    sécurité en <strong>cloisonnant</strong> les différents services. J'ai <strong>installé</strong> et <strong>configuré</strong> des services réseau essentiels, tels que 
                    <strong>Active Directory (AD), DNS et DHCP,</strong> en les <strong>adaptant</strong> aux besoins spécifiques de l'entreprise.
                    J'ai également mis en place des <strong>règles de communication inter-zones</strong> et configuré un <strong>firewall</strong> pour sécuriser 
                    l'infrastructure tout en assurant un <strong>fonctionnement fluide</strong> et efficace des services internes et externes.
                    Ce projet m'a permis de développer mes compétences techniques en <strong>administration réseau et sécurité</strong>, ainsi que 
                    mes qualités professionnelles telles que l'implication dans le <strong>travail en équipe</strong>.
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
                
                <div class="icon icon-network"></div>
            </div>
            
            <div class="project-description">
                <p>
                    Dans le cadre de ce projet, j'ai <strong>analysé un réseau pour identifier ses failles et sécuriser l'infrastructure</strong> 
                    face à la montée des cyberattaques. J'ai configuré un réseau sécurisé, <strong>filtré le trafic utilisateur</strong>, déployé 
                    une <strong>solution de supervision SIEM avec Wazuh</strong>, et <strong>installé un proxy SSL</strong> pour protéger les communications chiffrées.
                    Ce projet m'a permis d'acquérir une approche complète de la sécurité réseau, de la <strong>détection des vulnérabilités</strong> 
                    à la surveillance et l'analyse des événements de sécurité. J'ai ainsi développé mes compétences techniques 
                    en <strong>sécurisation de réseau, exploitation de vulnérabilités, administration de SIEM et gestion des communications 
                    sécurisées</strong>.
                </p>
            </div>
        </div>

        <div class="project-card fade-in paused">
            <div class="project-top">
                <img src="images/Projet3.png" alt="Projet 3" class="project-image">

                <div class="project-info">
                    <h3>Projet 3</h3>
                    <p class="project-tags">Programmation et DevSecOps</p>
                </div>
                <div class="icon icon-code-xml"></div>
            </div>

            <div class="project-description">
                <p>
                    Dans le cadre de ce projet, j'ai conçu et <strong>développé</strong> mon propre cyberfolio, un <strong>portfolio numérique sécurisé</strong>, 
                    afin de présenter mes compétences en <strong>développement web full stack</strong>. J'ai maîtrisé les langages <strong>frontend 
                    (HTML, CSS, JavaScript)</strong> et <strong>backend (PHP, MySQL, Python/C)</strong>, et j'ai installé et configuré un <strong>environnement 
                    de travail local</strong> complet avec serveur web et éditeur de code. J'ai développé des <strong>pages statiques et dynamiques</strong>, 
                    en intégrant des <strong>bases de données MySQL</strong> pour gérer du contenu de <strong>manière interactive</strong>. J'ai également appliqué 
                    les bonnes pratiques de sécurité web, incluant le <strong>hachage des mots de passe</strong>, la <strong>validation des entrées</strong> et la 
                    <strong>sécurisation des requêtes SQL</strong>. Pour approfondir mes compétences, j'ai <strong>exploité des API</strong> (comme "Have I Been Pwned") 
                    et développé des <strong>outils de détection cryptographique</strong>. Ce projet m'a permis de structurer et valoriser mes compétences techniques 
                    tout en développant des savoir-faire professionnels : <strong>gestion de projet en mode agile, travail en équipe, présentation des choix techniques 
                    lors de soutenances, et constitution d'un portfolio professionnel</strong> prêt à être présenté sur le marché du travail.
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
        <p>Voici la liste de mes compétences acquises au cours de ma vie</p>
    <div class="skills-container">
    <div class="skill"> HTML</div>
    <div class="skill"> CSS</div>
    <div class="skill"> Réseau</div>
    <div class="skill"> PHP</div>
    <div class="skill"> MySQL</div>
    <div class="skill"> Croissantage</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>
    <div class="skill"> Git</div>

    
    </div>
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