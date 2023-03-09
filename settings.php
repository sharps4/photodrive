<?php
session_start();

// Vérifier si l'utilisateur est connecté   
if (isset($_SESSION['user_id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Traitement du formulaire de modification de nom et prénom
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier si le prénom a été renseigné
        if (!empty($_POST['firstname'])) {
            $new_firstname = $_POST['firstname'];
            $user['prenom'] = $new_firstname;
        } else {
            $new_firstname = $user['prenom'];
        }

        // Vérifier si le nom a été renseigné
        if (!empty($_POST['lastname'])) {
            $new_lastname = $_POST['lastname'];
            $user['nom'] = $new_lastname;
        } else {
            $new_lastname = $user['nom'];
        }

        // Vérifier si une photo de profil a été soumise
        if (!empty($_FILES['profile_picture']['tmp_name'])) {
            $profile_picture = file_get_contents($_FILES['profile_picture']['tmp_name']);
            $stmt = $pdo->prepare('UPDATE users SET prenom = :prenom, nom = :nom, profile_picture = :profile_picture WHERE id = :id');
            $stmt->execute([
                'prenom' => $new_firstname,
                'nom' => $new_lastname,
                'profile_picture' => $profile_picture,
                'id' => $_SESSION['user_id']
            ]);
            $user['profile_picture'] = $profile_picture;
        } else {
            $stmt = $pdo->prepare('UPDATE users SET prenom = :prenom, nom = :nom WHERE id = :id');
            $stmt->execute([
                'prenom' => $new_firstname,
                'nom' => $new_lastname,
                'id' => $_SESSION['user_id']
            ]);
        }
        echo '<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> Données enregistrées avec succès !</div>';
    }
} else {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>PhotoDrive - Paramètres</title>
</head>
<body>
<div class="container">
    <div class="infos">
        <a href="profil.php" class="back">Retour</a>
    </div><br>

    <h2 class="subtitle">Modifier vos informations personnelles</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="firstname">Prénom :</label>
        <input type="text" name="firstname" placeholder="<?php echo $user['prenom']; ?>"><br>

        <label for="lastname">Nom :</label>
        <input type="text" name="lastname" placeholder="<?php echo $user['nom']; ?>"><br>

        <label for="profile_picture">Photo de profil :</label>
        <input type="file" name="profile_picture"><br>

        <input type="submit" value="Enregistrer">
    </form>


</div>
</body>
</html>
