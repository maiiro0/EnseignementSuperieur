<?php 
session_start();
$active = "calendrier";
?>

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
