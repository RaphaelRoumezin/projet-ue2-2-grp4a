# Table membre
- id (int, primary, autoincrement): Identifiant numérique du membre
- name (VARCHAR(20), primary): Nom d'utilisateur du membre
- fullname (VARCHAR(100)): Nom complet
- password (VARCHAR(32)): Hash du mot de passe du membre
- description (TEXT): Description du membre
- photo (VARCHAR(100)): Chemin d'accès relatif vers la photo du membre

# Table message
- id (int, primary, autoincrement): Identifiant numérique du message
- to (int, references membre.id): Destinataire du message
- nom (VARCHAR(100)): Nom de l'éditeur
- prenom (VARCHAR(100)): Prénom de l'éditeur
- genre (VARCHAR(2)): Genre de l'éditeur
- tel (VARCHAR(10)): Téléphone de l'éditeur
- email (VARCHAR(250)): Email de l'éditeur
- sujet (TEXT): Sujet du message
- content (TEXT): Contenu du message
