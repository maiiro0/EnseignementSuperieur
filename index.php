
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de conexxion</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
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
    // Démarre la session (stockée sur le serveur)
    session_start();
    
    require_once('connexion.php');

    // Vérifie que les champs du formulaire sont remplis
    if($_SERVER['REQUEST_METHOD'] ==+ 'POST ') {
       
        // On récupère les données dans des variables
        $email = $_POST['email'];
        $password = $_POST['password']; 
       
        //Je cherche si l'utilisateur possède bien cet email dans la base de donnée

        $query = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?"); 
        $query->execute([$email]);
        $user = $query->fetch();
        
         //Je vérifie si le mot de passe est bien attribué a cet utilisateur

        if($user && password_verify($password, $user['email'])){ //remplacer mot_de_passe par le nom de la colonne sql
            
            // On stocke dans $_SESSION la connexion de l'utilisateur

            $_SESSION['user'] = $user['email']; 

            // Redirige vers la page calendrier
            header("Location: Calendrier.php");
            exit();
        } 
        else {
            echo ("nom d'utilisateur ou mot de passe incorrect");
        }
    } 
?>
