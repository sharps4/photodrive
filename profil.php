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
    <div class="title-container">
        <div class="username">
            <span>Bonjour, <span class="span2"><?php echo $user['prenom']; echo $user['nom'];?></span></span>
            <a href="logout.php">Déconnexion</a>
        </div><br><?php
        echo ('<form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image">
        <input type="submit" value="Upload">
        </form>');
        ?>
    </div>
    <div><?php 

        // Récupération des images
        $stmt = $pdo->query("SELECT * FROM images");
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Affichage des images
        foreach ($images as $image) {
        echo '<img src="data:' . $image['type'] . ';base64,' . base64_encode($image['data']) . '">';
        }
    
    ?></div>
<!-- Sinon, afficher les boutons S'inscrire et Se connecter -->
<?php else : ?>
    <div class="title-container">
        <div class="signup">
            <a href="signup.html">Se connecter</a>
        </div>
        <div class="signin">
            <a href="signin.html">S'inscrire</a>
        </div>
    </div>
<?php endif; ?>

<br> <br>