<?php
require_once 'inclus/auth_check.php';
require_once 'inclus/Connexion.php';
require_once '../database/User_database.php';
require_once 'inclus/Header.php';
$active='types';?>

<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb"> <!-- Fil d'ariane -->
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Type_intervention.php">Types intervention</a>
            <p>></p>
            <a href="#">Cours</a>
        </div>

    <?php 
    if (isset($_GET['id'])) { // Vérification que l'id du type d'intervention est présent dans l'URL
        $id = htmlspecialchars($_GET['id']);
        $contenu = infos_intervention_type($con, $id); // Récupération des informations du type d'intervention à partir de son id
    }
    ?>
    
    <section class="intervention_sheet">
        <h3>Cours</h3>
        <form action="" method="post">
            <div class="form-align">
                <div>
                    <label for="" name="name" require>Nom - champ obligatoire</label>
                    <input type="text" name="name" value="<?php echo $contenu['name']?>"> <!-- Champ de saisie du nom du type d'intervention, ce champ est obligatoire -->
                </div>
                <div>
                    <label for="" name="color" require>Code couleur (hexadécimal) - champ obligatoire</label>
                    <input type="text" name="color" value="<?php echo $contenu['color']?>"> <!-- Champ de saisie du code couleur du type d'intervention, ce champ est obligatoire -->
                </div>
            </div>
            <div class="desc_form_update">
                <label for="" name="description" require>Description - champ obligatoire</label>
                <input class="input_desc" type="text" name="description" value="<?php echo $contenu['description']?>"> <!-- Champ de saisie de la description du type d'intervention, ce champ est obligatoire -->
            </div>

            <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
                <button type="button" command="show-modal" commandfor="supp" class="red-button selection">Supprimer</button>
                <button type="submit" class="blue-button selection">Enregistrer les informations</button>
            </div>
        </form>


        <dialog id="supp"> <!-- Fenêtre modale de confirmation de suppression du type d'intervention -->
            <button commandfor="supp" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button> 
            <div class="add-intervention">
                <img src="assets/Croix.png" alt="">
                <div>
                    <h3>Supprimer le type d'intervention</h3>
                    <p>Confirmation de l'action</p>
                </div>
            </div>
            <div>
                <div>
                    <p>Vous vous apprêtez à supprimer le type d'intervention,</p>
                    <p>cette action est irrévoquable.</p>
                    <p>A noter qu'aucune intervention de doit être liée à ce module pour pouvoir le supprimer.</p>
                    <br>
                    <p>Confirmez-vous l'action ?</p>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="pass">
                    <div class="button-form">
                        <button type="submit" class="grey-button selection" commandfor="supp" command="close">Annuler</button>
                        <button class="red-button selection" type="submit" name="action" value="confirm-delete">Confirmer</button>  
                    </div>
                </form>
            </div>
        </dialog>
    </section>
</body>
</html>


<?php
if (isset($_POST['action']) && $_POST['action'] === 'confirm-delete') { // Vérification que le bouton de confirmation de suppression a été cliqué
    $multi_id = id_course($id, $con);
    if (!empty($multi_id)) {
        echo "<p>"."Il existe des cours liés à ce type d'intervention, veuillez les supprimer avant de supprimer ce type d'intervention"."</p>"; 
        exit(); // Si des cours sont liés à ce type d'intervention, message d'erreur et arrêt de l'exécution du code pour éviter de supprimer le type d'intervention alors qu'il est encore lié à des cours
    }
    else {
        delete_intervention_type($con, $id); // Suppression du type d'intervention
    }
}


if ((!empty($_POST['name'])) && !empty($_POST['color']) && !empty($_POST['description'])) {  // Vérification des champs obligatoires
    if ($_POST['color'][0] == "#"){ // Vérification que le code couleur commence par un # pour être au format hexadécimal
        $name = htmlspecialchars($_POST['name']);
        $color = htmlspecialchars($_POST['color']);
        $description = htmlspecialchars($_POST['description']);
        update_intervention_type($con, $id, $name, $color, $description); // Màj du type d'intervntion
    }
    else {
        echo "<p>"."Vous n'avez pas mis d'hexadecimal"."</p>"; // Si le code couleur n'est pas au format hexadécimal, message d'erreur
    }
}
?>