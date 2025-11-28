<?php
    include "../interne/db.php";

    $statuts = [];

    // Démarrage de la session
    session_start();

    // Vérification de la connexion
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        http_response_code(302);
        exit();
    }

    $query = $db->prepare("SELECT fullname FROM membre WHERE id = :id");
    $query->execute(['id' => $_SESSION['user_id']]);
    $userData = $query->fetch();

    if (!$userData) {
        // L'utilisateur n'existe pas, paradoxe
        header("Location: logout.php");
        http_response_code(400);
        exit();
    }

    // Récéption du formulaire d'ajout de compétence
    if (isset($_POST['ajout_competence'])) {
        $nom = $_POST['nom'] ?? '';

        if ($nom == '') {
            $statuts[] = ["warning", "Le nom de la compétence à ajouter est obligatoire."];
        } else {
            // Insertion de la compétence
            $query = $db->prepare("INSERT INTO competence (membre, nom) VALUES (:membre, :nom)");
            $query->execute([
                'membre' => $_SESSION['user_id'],
                'nom' => $nom
            ]);

            $statuts[] = ["success", "Compétence ajoutée avec succès."];
        }
    }

    // Récupération des compétences
    $query = $db->prepare("SELECT * FROM competence WHERE membre = :id");
    $query->execute(['id' => $_SESSION['user_id']]);
    $competences = $query->fetchAll();

    // Récéption du formulaire d'ajout/modification d'expérience
    if (isset($_POST['experience_id'])) {
        $valide = true;

        $experience_id = $_POST['experience_id'] ?? '';
        if ($experience_id != '' && is_numeric($experience_id)) {
            $experience_id = intval($experience_id);
        } else {
            // Erreur fatale, car hors de controle de l'utilisateur
            die("ID d'expérience invalide.");
        }

        $poste = $_POST['poste'] ?? '';
        if ($poste == '') {
            $valide = false;
            $statuts[] = ["danger", "L'intitulé de poste est obligatoire."];
        }

        $entreprise = $_POST['entreprise'] ?? '';
        if ($entreprise == '') {
            $valide = false;
            $statuts[] = ["danger", "L'entreprise est obligatoire."];
        }

        $duree = $_POST['duree'] ?? '';
        if ($duree == '') {
            $valide = false;
            $statuts[] = ["danger", "La durée est obligatoire."];
        }

        $description = $_POST['description'] ?? '';
        if ($description == '') {
            $valide = false;
            $statuts[] = ["danger", "La description est obligatoire."];
        }

        // Récupération de l'image
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Upload du fichier
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $tmpName = $_FILES['image']['tmp_name'];
            $originalName = basename($_FILES['image']['name']);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = uniqid('exp_') . '.' . $extension;
            $imagePath = "uploads/" . $newName;

            if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
                $valide = false;
                $statuts[] = ["danger", "Erreur lors du téléchargement de l'image."];
            }
        }

        if ($valide) {
            if ($experience_id === -1) {
                // Insertion
                $query = $db->prepare("INSERT INTO experience (membre, poste, entreprise, duree, image, description) VALUES (:membre, :poste, :entreprise, :duree, :image, :description)");
                $query->execute([
                    'membre' => $_SESSION['user_id'],
                    'poste' => $poste,
                    'entreprise' => $entreprise,
                    'duree' => $duree,
                    'image' => $imagePath,
                    'description' => $description
                ]);

                $statuts[] = ["success", "Expérience ajoutée avec succès."];
            } else {
                // Mise à jour
                $query = $db->prepare("UPDATE experience SET poste = :poste, entreprise = :entreprise, duree = :duree, description = :description" . (($imagePath != '') ? ", image = :image" : "") . " WHERE id = :id AND membre = :membre");
                $params = [
                    'poste' => $poste,
                    'entreprise' => $entreprise,
                    'duree' => $duree,
                    'description' => $description,
                    'id' => $experience_id,
                    'membre' => $_SESSION['user_id']
                ];
                if ($imagePath != '') {
                    $params['image'] = $imagePath;
                }
                $query->execute($params);

                $statuts[] = ["success", "Expérience mise à jour avec succès."];

                // 
            }
        }
    }

    // Récupération des expériences
    $query = $db->prepare("SELECT * FROM experience WHERE membre = :id");
    $query->execute(['id' => $_SESSION['user_id']]);
    $experiences = $query->fetchAll();

    // Récupération des messages reçus
    $query = $db->prepare("SELECT * FROM message WHERE destinataire = :id ORDER BY id DESC");
    $query->execute(['id' => $_SESSION['user_id']]);
    $messages = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Relation avec bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Relation avec notre css -->
    <link rel="stylesheet" href="../index.css">

    <style>
        main {
            min-width: calc(100vw - 60px);
            margin-top: 20px;
        }

        h2 {
            margin-top: 30px;
        }

        .zone-ajout-competence {
            max-width: 600px;
        }

        a.lien-suppression {
            color: unset;
            text-decoration: none;
        }

        a.lien-suppression:hover {
            text-decoration: line-through;
        }

        table.experiences {
            width: 100%;
            border: 1px solid #ccc;
        }

        table.experiences th, table.experiences td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table.experiences tr.editable td, table.experiences .actions {
            padding: 1px;
        }
    </style>

    <title>Dashboard</title>
</head>

<body>
    <header>
        <!-- Titre sur notre page -->
        <h1 class="titre">Dashboard</h1>
    </header>
    <main>
        Connecté en tant que <?= htmlspecialchars($userData['fullname']) ?>. <a href="logout.php">Se déconnecter</a>

        <div class="zone-statuts">
            <?php foreach ($statuts as [$type, $contenu]) : ?>
                <div class="alert alert-<?= $type ?>" role="alert">
                    <?= htmlspecialchars($contenu) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Mes compétences</h2>

        <?php if (count($competences) === 0) : ?>
            <p>Vous n'avez pas encore ajouté de compétence.</p>
        <?php else : ?>
            <ul>
                <?php foreach ($competences as $competence) : ?>
                    <li>
                        <a class="lien-suppression" href="suppression.php?type=competence&id=<?= $competence['id'] ?>"><?= htmlspecialchars($competence['nom']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="ajout_competence" value="1">

            <div class="input-group zone-ajout-competence">
                <input name="nom" type="text" class="form-control" placeholder="Cybersécurité" required>
                <input class="btn btn-outline-primary" type="submit" value="Ajouter une compétence">
            </div>
        </form>

        <h2>Mes expériences</h2>

        <?php 
            function ligneExperience($id, $poste, $entreprise, $duree, $image, $description) {
                return "<tr>
                    <td>" . htmlspecialchars($poste) . "</td>
                    <td>" . htmlspecialchars($entreprise) . "</td>
                    <td>" . htmlspecialchars($duree) . "</td>
                    <td><img src='../" . htmlspecialchars($image) . "' alt='" . htmlspecialchars($poste) . "' style='max-width: 100px;'></td>
                    <td>" . htmlspecialchars($description) . "</td>
                    <td class='actions'>
                        <a href='?edit_experience=" . urlencode($id) . "' class='btn btn-secondary'>Modifier</a>
                        <a href='suppression.php?type=experience&id=" . urlencode($id) . "' class='btn btn-danger'>Supprimer</a>
                    </td>
                </tr>";
            }

            function ligneExperienceEditable($id, $poste, $entreprise, $duree, $description) {
                // action='.' permet de sortir du mode édition après soumission (en enlevant le paramètre GET)
                return "<tr class='editable'><form method='post' action='.' enctype='multipart/form-data'>
                    <input type='hidden' name='experience_id' value='$id'>
                    <td><input type='text' class='form-control' name='poste' value='" . htmlspecialchars($poste) . "'></td>
                    <td><input type='text' class='form-control' name='entreprise' value='" . htmlspecialchars($entreprise) . "'></td>
                    <td><input type='text' class='form-control' name='duree' value='" . htmlspecialchars($duree) . "'></td>
                    <td><input type='file' class='form-control' name='image'></td>
                    <td><input type='text' class='form-control' name='description' value='" . htmlspecialchars($description) . "'></td>
                    <td class='actions'><input type='submit' class='btn btn-primary' value='Enregistrer'></td>
                    </form></tr>";
            }
        ?>

        <table class="experiences">
            <thead>
                <tr>
                    <th>Poste occupé</th>
                    <th>Entreprise</th>
                    <th>Durée</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $edit_experience = $_GET['edit_experience'] ?? -1;
                    if (is_numeric($edit_experience)) {
                        $edit_experience = intval($edit_experience);
                    } else {
                        $edit_experience = -1;
                    }

                    foreach ($experiences as $experience) {
                        if ($experience['id'] === $edit_experience) {
                            // Ligne éditable
                            echo ligneExperienceEditable($experience['id'], $experience['poste'], $experience['entreprise'], $experience['duree'], $experience['description']);
                        } else {
                            // Ligne normale
                            echo ligneExperience($experience['id'], $experience['poste'], $experience['entreprise'], $experience['duree'], $experience['image'], $experience['description']);
                        }
                    } 
                ?>

                <!-- Ligne d'ajout d'expérience (si pas de ligne en modification) -->
                <?php 
                    if ($edit_experience === -1) {
                        echo ligneExperienceEditable(-1, '', '', '', '');
                    }
                ?>
            </tbody>
        </table>

        <h2>Messages recus</h2>

        <?php if (count($messages) === 0) : ?>
            <p>Vous n'avez pas encore reçu de message.</p>
        <?php else : ?>
            <?php foreach ($messages as $message) : ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><?= htmlspecialchars($message['nom']) ?> <?= htmlspecialchars($message['prenom']) ?> - <?= htmlspecialchars($message['sujet']) ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <small class="text-body-secondary">
                                Genre:
                                <?php
                                    switch ($message['genre']) {
                                        case 'm':
                                            echo "Homme";
                                            break;
                                        case 'f':
                                            echo "Femme";
                                            break;
                                        case 'c':
                                            echo "Croissant";
                                            break;
                                        case 'a':
                                            echo "Autre";
                                            break;
                                        default:
                                            echo "Non précisé";
                                            break;
                                    }
                                ?>
                                / Tel: +33 <?= htmlspecialchars($message['tel']) ?>
                                / Email: <?= htmlspecialchars($message['email']) ?>
                            </small>

                            <br>
                        
                            <?= nl2br(htmlspecialchars($message['content'])) ?>
                        </p>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <!-- footer en bas de la page grace a notre liaison a index.css -->
    <footer>
        &copy; <?= date("Y") ?> Nos portfolios. Tous droits réservés.
    </footer>
</body>

</html>