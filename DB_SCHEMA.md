# Table membre
- id (int, primary, autoincrement): Identifiant numérique du membre
- name (VARCHAR(20), primary): Nom d'utilisateur du membre
- fullname (VARCHAR(100)): Nom complet
- password (VARCHAR(255)): Hash du mot de passe du membre
- intro (TEXT): Introduction du membre sur la page d'accueil
- description (TEXT): Description du membre
- photo (VARCHAR(100)): Chemin d'accès relatif vers la photo du membre

# Table message
- id (int, primary, autoincrement): Identifiant numérique du message
- destinataire (int, references membre.id): Destinataire du message
- nom (VARCHAR(100)): Nom de l'éditeur
- prenom (VARCHAR(100)): Prénom de l'éditeur
- genre (VARCHAR(2)): Genre de l'éditeur
- tel (VARCHAR(10)): Téléphone de l'éditeur
- email (VARCHAR(250)): Email de l'éditeur
- sujet (TEXT): Sujet du message
- content (TEXT): Contenu du message

# Table reseau_social
- id (int, primary, autoincrement): Identifiant numérique
- membre (int, references membre.id): Membre auquel appartient la connexion
- nom (VARCHAR(20)): Nom du réseau social
- icone (VARCHAR(100)): Chemin d'accès relatif vers l'icone du réseau social
- url (VARCHAR(100)): Lien de la page du réseau social

# Table competence
- id (int, primary, autoincrement): Identifiant numérique
- membre (int, references membre.id): Membre auquel appartient la compétence
- nom (VARCHAR(100)): Nom de la compétence

# Table experience
- id (int, primary, autoincrement): Identifiant numérique
- membre (int, references membre.id): Membre auquel appartient l'expérience
- image (VARCHAR(100)): Image de la carte (comme le logo de l'entreprise)
- entreprise (VARCHAR(150)): Nom de l'entreprise
- poste (VARCHAR(150)): Intitulé de poste
- duree (VARCHAR(50)): Temps d'occupation
- description (TEXT): Description de l'experience