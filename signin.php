<?php
// récupérer les données du formulaire
$nom = $_POST['nom']; // 
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mdp = $_POST['mdp'];
// $error_tmp;


// foreach($_POST as $cle => $valeur){
//     if (!isset($_POST[$cle]) || empty($_POST[$cle])){
//         $error_tmp = $cle;
//         break;
//     }
// };

// if($error_tmp !== null){
//     echo "vous n'avez cle suivante";
// };

$hashed_password = password_hash($mdp, PASSWORD_DEFAULT);

// se connecter à la base de données avec PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');
} catch (PDOException $e) {
    die("La connexion à la base de données a échoué : " . $e->getMessage());
}


// préparer la requête d'insertion des données dans la base de données
$stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)");
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':prenom', $prenom);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':mdp', $hashed_password);

// exécuter la requête
if ($stmt->execute()) {
    echo "Les données ont été enregistrées avec succès.";
} else {
    echo "Erreur : " . $stmt->errorInfo()[2];
}

// fermer la connexion
$pdo = null;
?>
