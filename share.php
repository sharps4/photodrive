<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>PhotoDrive - Share</title>
</head>
<body>
<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM images WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $_GET['id'], 'user_id' => $_SESSION['user_id']]);
        $image = $stmt->fetch();

        if ($image) {
            // lien unique avec une date d'expiration
            $expiration_date = date('Y-m-d H:i:s', strtotime('+4 hour'));
            $link = uniqid();
            $stmt = $pdo->prepare('INSERT INTO shares (image_id, link, expiration_date) VALUES (:image_id, :link, :expiration_date)');
            $stmt->execute(['image_id' => $_GET['id'], 'link' => $link, 'expiration_date' => $expiration_date]);

            // Supprimer les liens expirés de la base de données
            $stmt = $pdo->prepare('DELETE FROM shares WHERE expiration_date < NOW()');
            $stmt->execute();

            // Afficher le lien
            $share_link = 'http://localhost/photoDrive/image.php?share=' . $link;
            echo '<h2 class="subtitle">Voici le lien partageable pour cette image:</h2>';
            echo '<div class="share-link"><a href="' . $share_link . '">' . $share_link . '</a></div>';
        } else {
            echo 'Cette image n\'existe pas.';
        }
    } else {
        echo 'Aucune image sélectionnée.';
    }
} else {
    header('Location: signin.html');
}

?>
</body>
</html>