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
        <div class="breadcrumb">
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Type_intervention.php">Types intervention</a>
            <p>></p>
            <a href="#">Cours</a>
        </div>

    <?php 
    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $contenu = infos_intervention_type($con, $id);
    }
    ?>
    
    <section class="intervention_sheet">
        <h3>Cours</h3>
        <form action="" method="post">
            <div class="form-align">
                <div>
                    <label for="" name="name" require>Nom - champ obligatoire</label>
                    <input type="text" name="name" value="<?php echo $contenu['name']?>">
                </div>
                <div>
                    <label for="" name="color" require>Code couleur (hexadécimal) - champ obligatoire</label>
                    <input type="text" name="color" value="<?php echo $contenu['color']?>">
                </div>
            </div>
            <div class="desc_form_update">
                <label for="" name="description" require>Description - champ obligatoire</label>
                <input class="input_desc" type="text" name="description" value="<?php echo $contenu['description']?>">
            </div>

            <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
                <button type="button" command="show-modal" commandfor="supp" class="red-button selection">Supprimer</button>
                <button type="submit" class="blue-button selection">Enregistrer les informations</button>
            </div>
        </form>


        <dialog id="supp">
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
if (isset($_POST['action']) && $_POST['action'] === 'confirm-delete') {
    $multi_id = id_course($id, $con);
    if (!empty($multi_id)) {
        echo "<p>"."Il existe des cours liés à ce type d'intervention, veuillez les supprimer avant de supprimer ce type d'intervention"."</p>";
        exit();
    }
    else {
        delete_intervention_type($con, $id);
    }
}


if ((!empty($_POST['name'])) && !empty($_POST['color']) && !empty($_POST['description'])) {
    if ($_POST['color'][0] == "#"){
        $name = htmlspecialchars($_POST['name']);
        $color = htmlspecialchars($_POST['color']);
        $description = htmlspecialchars($_POST['description']);
        update_intervention_type($con, $id, $name, $color, $description);
    }
    else {
        echo "<p>"."Vous n'avez pas mis d'hexadecimal"."</p>";
    }
}
?>