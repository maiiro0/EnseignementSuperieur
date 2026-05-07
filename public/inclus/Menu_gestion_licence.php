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
            <strong>Lycée Saint-Vincent</strong><br> <!-- Nom de l'établissement -->
            <span>Enseignement Supérieur</span>
        </div>
    </div>
    <div class="menu-section">
        <div class="menu-section-title">MENU</div>
            <a href="Calendrier.php" class="menu-item <?php if($active=='calendrier') echo 'active-item-menu'; ?>"> <!-- Lien vers la page de calendrier, si la variable $active est égale à 'calendrier', on ajoute la classe 'active-item-menu' pour indiquer que c'est la page active -->
            <img src="<?php if($active=='calendrier') echo 'assets/icon1.png'; else echo 'assets/Blue-calendar.png'?>" class="menu-icon"> <!-- Si la page active est le calendrier, on affiche l'icône du calendrier en blanc, sinon on affiche l'icône du calendrier en bleu -->
            Calendrier </a>

            <a href="Intervention.php" class="menu-item <?php if($active=='interventions') echo 'active-item-menu'; ?>"> <!-- Lien vers la page des interventions, si la variable $active est égale à 'interventions', on ajoute la classe 'active-item-menu' pour indiquer que c'est la page active -->
            <img src="<?php if($active=='interventions') echo 'assets/White-intervention.png'; else echo 'assets/icon2.png'?>" class="menu-icon"> <!-- Si la page active est les interventions, on affiche l'icône des interventions en blanc, sinon on affiche l'icône des interventions en bleu -->
            Interventions
            </a>

            <a href="Corps_enseignant.php" class="menu-item <?php if($active=='enseignants') echo 'active-item-menu'; ?>"> <!-- Lien vers la page du corps enseignant, si la variable $active est égale à 'enseignants', on ajoute la classe 'active-item-menu' pour indiquer que c'est la page active -->
            <img src="<?php if($active=='enseignants') echo 'assets/white-corps-enseignant.png'; else echo 'assets/icon3.png'?>" class="menu-icon"> <!-- Si la page active est le corps enseignant, on affiche l'icône du corps enseignant en blanc, sinon on affiche l'icône du corps enseignant en bleu -->
            Corps enseignant
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">PARAMÉTRAGE</div>
                <a href="Modules.php" class="menu-item <?php if($active=='modules') echo 'active-item-menu'; ?>"> <!-- Lien vers la page des modules, si la variable $active est égale à 'modules', on ajoute la classe 'active-item-menu' pour indiquer que c'est la page active -->
                <img src="<?php if($active=='modules') echo 'assets/white-module.png'; else echo 'assets/icon4.png'?>" class="menu-icon"> <!-- Si la page active est les modules, on affiche l'icône des modules en blanc, sinon on affiche l'icône des modules en bleu -->
                Modules
                </a>

                <a href="Type_intervention.php" class="menu-item <?php if($active=='types') echo 'active-item-menu'; ?>"> <!-- Lien vers la page des types d'intervention, si la variable $active est égale à 'types', on ajoute la classe 'active-item-menu' pour indiquer que c'est la page active -->
                <img src="<?php if($active=='types') echo 'assets/white-type-intervention.png'; else echo 'assets/icon5.png'?>" class="menu-icon"> <!-- Si la page active est les types d'intervention, on affiche l'icône des types d'intervention en blanc, sinon on affiche l'icône des types d'intervention en bleu -->
                Types d’intervention
                </a>
            </div>
                
            <div class="user-menu">
                <img src="assets/image1.png" class="avatar-menu">
                <div>
                    <div class="flex">       
                        <p class="name-menu">Stella Ribas</p>
                        <a href="#dialog"><img src="assets/chevron-down.png" alt="" style="cursor: pointer;"></a> <!-- Lien pour ouvrir la modal de déconnexion, l'attribut href="#dialog" permet d'ouvrir la modal en ciblant l'id "dialog" de la div de la modal -->
                    </div>
                    <p class="menu-role">Administrateur</p>
                </div>
            </div>
            <div class="modal-deco" id="dialog"> <!-- Div de la modal de déconnexion, l'id "dialog" permet d'ouvrir la modal en ciblant cet id dans le lien de l'avatar -->
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
