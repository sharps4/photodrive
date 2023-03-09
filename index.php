<?php
session_start();

// Vérifier si l'utilisateur est connecté   
if (isset($_SESSION['user_id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    $_SESSION['user_id'] = $user['id'];
    header('Location: /photoDrive/profil.php'); // Redirection vers la page d'accueil
    exit();
}

else {
    echo '<a href="signin.html">S\'inscrire</a>';
    echo '<a href="signup.html">Se connecter</a>';
}
?>