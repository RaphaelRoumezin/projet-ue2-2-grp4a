<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Injection de mot de passe</title>
</head>
<body>
    <h1>Générateur d'update de mot de passe dans la DB</h1>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $pass = $_POST['pass'] ?? '';

            if ($name == '' || $pass == '') {
                echo "<p style='color: red;'>Le nom d'utilisateur et le mot de passe sont obligatoires.</p>";
            } else {
                // Hashage du mot de passe
                $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

                // Affichage de la requête SQL
                echo "<h2>Requête SQL générée :</h2>";
                echo "<pre>UPDATE `membre` SET `password` = '" . htmlspecialchars($hashedPass) . "' WHERE name = '" . htmlspecialchars($name) . "';</pre>";
            }
        }
    ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Nom d'utilisateur">
        <input type="password" name="pass" placeholder="Mot de passe">
        <input type="submit" value="Générer l'insertion">
    </form>
</body>
</html>