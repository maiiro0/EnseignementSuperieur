<?php
    // Vérification de la session
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
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
            <a href="Calendrier.php" class="menu-item <?php if($active=='calendrier') echo 'active-item-menu'; ?>">
            <img src="<?php if($active=='calendrier') echo 'assets/icon1.png'; else echo 'assets/Blue-calendar.png'?>" class="menu-icon">
            Calendrier </a>

            <a href="Intervention.php" class="menu-item <?php if($active=='interventions') echo 'active-item-menu'; ?>">
            <img src="<?php if($active=='interventions') echo 'assets/White-intervention.png'; else echo 'assets/icon2.png'?>" class="menu-icon">
            Interventions
            </a>

            <a href="Corps_enseignant.php" class="menu-item <?php if($active=='enseignants') echo 'active-item-menu'; ?>">
            <img src="<?php if($active=='enseignants') echo 'assets/white-corps-enseignant.png'; else echo 'assets/icon3.png'?>" class="menu-icon">
            Corps enseignant
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">PARAMÉTRAGE</div>
                <a href="Modules.php" class="menu-item <?php if($active=='modules') echo 'active-item-menu'; ?>">
                <img src="<?php if($active=='modules') echo 'assets/white-module.png'; else echo 'assets/icon4.png'?>" class="menu-icon">
                Modules
                </a>

                <a href="Type_intervention.php" class="menu-item <?php if($active=='types') echo 'active-item-menu'; ?>">
                <img src="<?php if($active=='types') echo 'assets/white-type-intervention.png'; else echo 'assets/icon5.png'?>" class="menu-icon">
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
                    <form action="Calendrier.php" method="get">
                        <input  class="red-button" type="submit" name="deconnexion" value="Déconnexion">
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
