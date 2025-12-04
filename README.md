# NosPortfolios

Ce site présente les portfolios de Ethan CHAUDAT, Simon DUCHANAUD et Raphaël ROUMEZIN.

## Fonctionnalités
- Page d'accueil avec liens vers les portfolios
- Pages individuelles pour chaque portfolio
- Formulaire de contact fonctionnel
- Animations lors du scroll (texte et images)
- Navbar active selon la section visible
- Mode jour/nuit
- Responsive design pour mobile et desktop
- Dashboard de gestion des contenus dynamique
- Authentification sécurisée avec hachage de mot de passes et 2FA avec Discord
- Test de fuites de mots de passe grâce à l'API Have I Been Pwned


## Technologies utilisées
- Apache
- HTML5
- CSS3
- JavaScript
- Bootstrap CSS
- Lucide icons
- PHP
- MariaDB
- Hachage Bcrypt

## Organisation des fichiers
- `images/` -> Toutes les images
- `uploads/` -> Images uploadés depuis le dashboard
- `js/` -> Fichiers JavaScript
- `css/` -> Fichiers CSS
- `CV/` -> CVs des membres du site
- `interne/` -> Librairies PHP, non accessibles directement par le client
- `.htaccess` -> Régule les fichiers lisibles par le client, pour Apache
- `dashboard/` -> Sous-partie du site, relative au dashboard et à l'authentification
- `*.php` -> Fichiers HTML, dynamisés avec PHP
- `env.ini` -> Variables d'environnement


## Instructions pour déployer le site en local
1. Cloner le dépôt
2. Démarrer un serveur Apache avec PHP
3. Créer une base de données avec les tables mentionnées dans [DB_SCHEMA.md](DB_SCHEMA.md), et la peupler. Les hachés de mot de passes peuvent être générés avec `/dashboard/inject.php`
4. Copier `sample.env.ini` en `env.ini`, et remplir les valeurs pour correspondre à votre environnement
5. Ouvrir le site dans un navigateur Web


## Auteurs
- Ethan CHAUDAT
- Simon DUCHANAUD
- Raphaël ROUMEZIN

## Mentions légales
Ce projet fait usage de:
- [Bootstrap](https://getbootstrap.com/) (Licence MIT)
- [Lucide Icons](https://lucide.dev/) (License MIT)


(c) 2025 Nos portfolios. Tous droits réservés.