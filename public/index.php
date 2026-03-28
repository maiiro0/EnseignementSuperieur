<?php
require_once 'inclus/Header.php'?>

<body>
    <div class="connexion-container">
        <div class="right-menu">
            <div class="menu-content">
                <img src="assets/logo 1.png">
                <div class="menu-text-align">
                    <p class="menu-typo1">Lycée Saint-Vincent</p>
                    <p class="menu-typo2">enseignement superieur</p>
                </div>
            </div>
        </div>
        <div class="connexion-form">
            <div>
                <h1>Gestion Supérieur</h1>
                <h2 class="txt-blue">Connexion au portail</h2>
                <form action="" method="POST">
                    <label>Email - champs obligatoire</label>
                    <input type="email" name="email" id="email"><br>
                    <label>Mot de passe - champs obligatoire</label>
                    <input type="password" name="password" id="password">
                    <button type="submit" name="login"">Accéder au portail</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $typedPassword = ($_POST['password']);

    $query = $con->prepare("SELECT username, password FROM utilisateurs WHERE username=:username");
    $query->bindParam(':username', $username);
    $query->execute();

    $user = $query->fetch(PDO::FETCH_ASSOC);
    // comparaison du username et password saisis avec ceux en BD
    if ($user['username'] && password_verify($typedPassword, $user['password'])) {
        $_SESSION['username'] = $username;
        // redirection vers la page calendrier
        header('Location: Calendrier.php');
        exit();
    } else {
        echo "Problème de connexion";
    }
}

?>
