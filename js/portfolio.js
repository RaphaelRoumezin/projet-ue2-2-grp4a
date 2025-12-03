// Séléction des éléments nécessaires
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".cool-navbar a");
const pausedAnimations = document.querySelectorAll(".fade-in.paused");

// ===== Gestion de la navigation et du lien actif =====

// Section active (correspond au lien souligné)
let currentSection = null;

// Fonction pour mettre à jour le lien actif
function updateActiveLink(id) {
    // Enlever l'ancienne classe active
    if (currentSection) {
        const oldLink = document.querySelector(`#link-${currentSection}`);
        if (oldLink) {
            oldLink.classList.remove("active");
        }
    }

    currentSection = id;

    // Ajouter la nouvelle classe active
    const activeLink = document.querySelector(`#link-${id}`);
    if (activeLink) {
        activeLink.classList.add("active");
    }
}

// Pour chaque section, enregistrer à partir de quel offset de scroll elle commence
const offsets = {};

sections.forEach(section => {
    offsets[section.id] = section.offsetTop;
});

// Mettre la section accueil comme la plus haute
offsets["accueil"] = -1;

// L'activation de la dernière section "compétences" se fait lorsque l'on atteint le bas de la page,
// si la section "compétences" est trop petite
const plusBassePosition = (document.documentElement.scrollHeight || document.body.scrollHeight) - window.innerHeight - 10;
if (offsets["competences"] < plusBassePosition) {
    offsets["competences"] = plusBassePosition;
}

// Fonction appelée pour mettre à jour la section active lors du scroll
function onScroll() {
    const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

    // Cherche la nouvelle section active
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

// ===== Animations au scroll =====

// Créer un IntersectionObserver pour détecter quand les éléments entrent dans le viewport
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.remove("paused");
            observer.unobserve(entry.target); // Arrêter d'observer une fois l'animation jouée
        }
    });
}, { threshold: 0.5 }); // Déclencher quand 50% de l'élément est visible

// Observer chaque élément avec l'animation en pause
pausedAnimations.forEach(el => {
    observer.observe(el);
});

// ===== Bouton secret =====
const secretButton = document.getElementById("secret-button");
const heroImage = document.querySelector(".hero img");
let secretClickCount = 0;

secretButton.addEventListener("click", () => {
    secretClickCount++;
    const duration = 5 / secretClickCount; // Vitesse augmente à chaque clic

    heroImage.classList.add("easteregg");
    heroImage.style.animationDuration = `${duration}s`;
});