<?php
session_start();

// Vérifier si l'utilisateur est connecté   
if (isset($_SESSION['user_id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>

<?php if (isset($user)) : ?>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>PhotoDrive - Profil</title>
</head>
<body>
<div class="container">
        <div class="infos">
        <?php if($user['profile_picture']) { ?>
            <img src="data:<?php echo $user['profile_picture_type']; ?>;base64,<?php echo base64_encode($user['profile_picture']); ?>" class="profile-pic">
        <?php } ?>
            <span>Bonjour, <span class="span2"><?php echo $user['prenom'] . ' ' . $user['nom']; ?></span></span>
            <a href="settings.php" class="settings-btn">Paramètres</a>
            <a href="shared_links.php" class="shared-btn">Vos liens partagés</a>
            <a href="logout.php" class="logout-btn">Déconnexion</a>

        </div><br>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image">
            <label for="files">Taille maximum: 2MO</label>
            <input type="submit" value="Upload">
        </form>
    </div>
    <div>
        <?php 

        // Récupération des images
        $stmt = $pdo->prepare("SELECT * FROM images WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<h2 class="subtitle">Vos photos</h2>';
        // Affichage des images et des boutons de suppression
        foreach ($images as $image) {
            echo '<div class="img-name">'.$image['name'].'</div>';
            echo '<div class="img-show"><img src="data:' . $image['type'] . ';base64,' . base64_encode($image['data']) . '"></div>';
            echo '<div class="delete-btn"><a href="delete.php?id=' . $image['id'] . '">Supprimer</a></div>';
            echo '<div class="share-btn"><a href="share.php?id=' . $image['id'] . '">Partager</a></div>'."<br>"."<br>"."<br>";
        }        

        ?>
    </div>
<?php else : ?>
    <!-- Sinon, afficher les boutons S'inscrire et Se connecter -->
    <div class="title-container">
        <div class="signup">
            <a href="signup.html">Se connecter</a>
        </div>
        <div class="signin">
            <a href="signin.html">S'inscrire</a>
        </div>
    </div>
<?php endif; ?>

</body>
</html>