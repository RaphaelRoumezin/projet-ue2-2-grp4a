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

// Mettre la dernière section comme la plus basse   
offsets["competences"] = document.body.scrollHeight - window.innerHeight - 10;

function onScroll() {
    const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

    let newSection = currentSection;
    
    // Pour tous les offset, trouver le plus grand offset inférieur à la position du scroll
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

// Définir comme actif le lien cliqué systematiquement
navLinks.forEach(el => {
    el.addEventListener("click", (e) => {
        if (e.target.id.startsWith("link-"))
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
}, { threshold: 0.5 }); // Déclencher quand 50% de l'élément est visible

pausedAnimations.forEach(el => {
    observer.observe(el);
});