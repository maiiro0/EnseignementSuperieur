<?php 
session_start();
$active = "calendrier";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="lateral-menu">
        <div class="logo-menu">
            <img src="assets/logo1.png">
            <div class="title-menu">
                <strong>Lycée Saint-Vincent</strong><br>
                <span>Enseignement Supérieur</span>
            </div>
        </div>
        <div class="menu-section">
            <div class="menu-section-title">MENU</div>
                <a href="#" class="menu-item <?php if($active=='calendrier') echo 'active-item-menu'; ?>">
                <img src="assets/icon1.png" class="menu-icon">
                Calendrier </a>

                <a href="#" class="menu-item <?php if($active=='interventions') echo 'active-item-menu'; ?>">
                <img src="assets/icon2.png" class="menu-icon">
                Interventions
                </a>

                <a href="#" class="menu-item <?php if($active=='enseignants') echo 'active-item-menu'; ?>">
                <img src="assets/icon3.png" class="menu-icon">
                Corps enseignant
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">PARAMÉTRAGE</div>
                    <a href="#" class="menu-item <?php if($active=='modules') echo 'active-item-menu'; ?>">
                    <img src="assets/icon4.png" class="menu-icon">
                    Modules
                    </a>

                    <a href="#" class="menu-item <?php if($active=='types') echo 'active-item-menu'; ?>">
                    <img src="assets/icon5.png" class="menu-icon">
                    Types d’intervention
                    </a>
                </div>
                    
                <div class="user-menu">
                    <img src="assets/image1.png" class="avatar-menu">
                    <div>
                        <div class="flex">       
                            <p class="name-menu">Stella Ribas</p>
                            <a href="#dialog"><img src="assets/chevron-down.png" alt="" style="cursor: pointer;"></a>
                        </div>
                        <p class="menu-role">Administrateur</p>
                    </div>
                </div>
                <div class="modal-deco" id="dialog">
                    <div class="modal-content">
                        <a style="cursor-pointer" href="#">Annuler</a>
                        <form action="#" method="get">
                            <input  class="red-button" type="submit" name="deconnexion" value="Déconnexion">
                        </form> 
                        <?php
                            if(isset($_GET['deconnexion'])) {
                                session_destroy();
                                header("Location: index.php");
                                exit();
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>