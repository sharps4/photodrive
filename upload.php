<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Vérifier si un fichier a été sélectionné
  if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    // Vérifier le type de fichier
    $allowed = array("image/jpeg", "image/png", "image/gif");
    if (in_array($_FILES["image"]["type"], $allowed)) {

      // Ouvrir le fichier pour le lire
      $fp = fopen($_FILES["image"]["tmp_name"], "rb");
      $content = fread($fp, filesize($_FILES["image"]["tmp_name"]));
      fclose($fp);

      // Connexion à la base de données
      $dsn = 'mysql:host=localhost;dbname=photodrive';
      $username = 'root';
      $pdo = new PDO($dsn, $username);

      // Préparer la requête
      $stmt = $pdo->prepare("INSERT INTO images (name, type, data) VALUES (:name, :type, :data)");
      $stmt->bindParam(':name', $_FILES["image"]["name"]);
      $stmt->bindParam(':type', $_FILES["image"]["type"]);
      $stmt->bindParam(':data', $content, PDO::PARAM_LOB);

      // Exécuter la requête 
      if ($stmt->execute()) {
        echo "Image uploadée avec succès.";
      } else {
        echo "Erreur lors de l'upload de l'image: " . $pdo->errorInfo()[2];
      }

      $stmt = null;
      $pdo = null;

    } else {
      echo "Seuls les fichiers JPEG, PNG et GIF sont autorisés.";
    }

  } else {
    echo "Veuillez sélectionner un fichier.";
  }

}