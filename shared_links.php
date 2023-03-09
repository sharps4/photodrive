<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>PhotoDrive - Mes images partagées</title>
</head>
<body>
<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');

    // Récupérer les liens partagés non expirés de l'utilisateur connecté
    $stmt = $pdo->prepare('SELECT images.*, shares.link, shares.expiration_date FROM images JOIN shares ON images.id = shares.image_id WHERE images.user_id = :user_id AND shares.expiration_date > NOW()');
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $shared_images = $stmt->fetchAll();

    if (count($shared_images) > 0) {
        // Afficher les liens partagés
        echo '<h2 class="subtitle">Mes images partagées:</h2>';
        foreach ($shared_images as $image) {
            $share_link = 'http://localhost/photoDrive/image.php?share=' . $image['link'];
            echo '<div class="shared-image">';
            echo '<img src="data:'.$image['type'].';base64,'.base64_encode($image['data']).'" alt="'.$image['name'].'">';
            echo '<div class="shared-image-info">';
            echo '<div class="shared-image-name">'.$image['name'].'</div>';
            echo '<div class="shared-image-date">Expire le '.date('d/m/Y à H:i', strtotime($image['expiration_date'])).'</div>';
            echo '<div class="shared-image-link"><a href="'.$share_link.'">'.$share_link.'</a></div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        // Aucun lien partagé
        echo '<div>Vous n\'avez partagé aucune image pour le moment.</div>';
    }
} else {
    header('Location: signin.html');
}
?>
</body>
</html>