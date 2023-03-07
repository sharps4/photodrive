<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');

// Récupération des données du formulaire
$email = $_POST['email'];
$mdp = $_POST['mdp'];
$hashed_password = password_hash($mdp, PASSWORD_DEFAULT);

// Requête pour récupérer l'utilisateur correspondant à l'email
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

// Vérification du mot de passe
// var_dump(password_verify($mdp, $hashed_password));
// die();
if ($user != null && password_verify($mdp, $hashed_password)) { 
    // Les informations sont correctes, on connecte l'utilisateur
    session_start();
    $_SESSION['user_id'] = $user['id'];
    header('Location: /photoDrive/profil.php'); // Redirection vers la page d'accueil
    exit();
} else {
    echo 'Adresse email ou mot de passe incorrect.';
}
?>