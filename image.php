<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>PhotoDrive - Image</title>
</head>
<body>
<?php
session_start();

if (isset($_GET['share'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');

    // Récupérer l'image à partir de l'identifiant de partage
    $stmt = $pdo->prepare('SELECT images.*, shares.expiration_date, users.nom, users.prenom FROM images JOIN shares ON images.id = shares.image_id JOIN users ON users.id = images.user_id WHERE shares.link = :link');
    $stmt->execute(['link' => $_GET['share']]);
    $image = $stmt->fetch();

    if ($image !== false) { // Vérifie si $image n'est pas égal à false
        // Vérifier si le lien a expiré
        $expiration_date = new DateTime($image['expiration_date']);
        $now = new DateTime();
        if ($expiration_date < $now) {
            // Le lien a expiré, afficher un message d'erreur
            echo '<div>Le lien de partage a expiré.</div>';
            exit();
        }
    }
}


if (isset($image) && $image !== false) { // Vérifie si $image n'est pas égal à false

    // Vérifier la taille de l'image (maximum 2 Mo)
    if (strlen($image['data']) > 2097152) {
        echo '<div>L\'image est trop grande (maximum 2 Mo).</div>';
        exit();
    }

    // Afficher le nom de l'image
    echo '<div class="img-name">Nom de l\'image : <span class="span3">'.$image['name'].'</span></div>';

    // Afficher l'image
    echo '<div class="img-show"><img src="data:'.$image['type'].';base64,'.base64_encode($image['data']).'"></div>';

    // Bouton de téléchargement
    echo '<div class="download-btn"><a href="data:'.$image['type'].';base64,'.base64_encode($image['data']).'" download="'.$image['name'].'">Télécharger l\'image</a></div>';

    // Afficher le nom et prénom de l'utilisateur qui a partagé l'image
    echo '<div class="img-text">Image partagée par '.$image['prenom'].' '.$image['nom'].'</div>';

} else {
    // Afficher un message d'erreur
    echo '<div>L\'image demandée n\'existe pas ou vous n\'avez pas le droit de la voir.</div>';
}

?>
</body>
</html>