const nuitjour = document.getElementById('nuitjour');
const body = document.body;

//  Changer le thème entre clair et sombre lors du clic sur le bouton
nuitjour.addEventListener('click', (event) => {
    event.preventDefault();

    if (body.getAttribute('data-bs-theme') === 'dark') {
        localStorage.setItem("theme", "light");
        body.setAttribute('data-bs-theme', 'light');
    } else {
        localStorage.setItem("theme", "dark");
        body.setAttribute('data-bs-theme', 'dark');
    }
});

// Initialiser le thème en fonction de la préférence système ou du stockage local
if (localStorage.getItem("theme") === null) {
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
    if (prefersDarkScheme) {
        body.setAttribute('data-bs-theme', 'dark');
    }
} else {
    const savedTheme = localStorage.getItem("theme") || "light";
    body.setAttribute('data-bs-theme', savedTheme);
}