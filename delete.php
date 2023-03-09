<?php
session_start();

session_start();

// Vérifier si l'utilisateur est connecté   
if (isset($_SESSION['user_id'])) {

    // Vérifier si l'image existe
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=photodrive', 'root');

        // Suppression de la ligne(s) de la table 'shares' qui référence l'image
        $stmt = $pdo->prepare('DELETE FROM shares WHERE image_id = :id');
        $stmt->execute(['id' => $id]);

        // Suppression de l'image de la base de données
        $stmt = $pdo->prepare('DELETE FROM images WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Affichage d'un message de succès
        header('Location: profil.php');
        echo "L'image a été supprimée avec succès.";

    } else {
        // Pas d'ID fourni
        echo "Aucune image sélectionnée.";
    }
} else {
    // L'utilisateur n'est pas connecté
    header('Location: signin.html');
}