<?php
require_once 'inclus/Header.php'
require_once '../database/User_database.php';
require_once 'inclus/Connexion.php';?>

<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Ajouter type intervention</p>
        </div>
    
    <section class="intervention_sheet">
        <h3>Intervention</h3>
        <form action="" method="post">
            <div class="form-align">
                <div>
                    <label for="" name="name" require>Nom - champ obligatoire</label>
                    <input type="text" name="name" placeholder='Saisir un nom'>
                </div>
                <div>
                    <label for="" name="color" require>Code couleur (hexadécimal) - champ obligatoire</label>
                    <input type="text" name="color" placeholder='#DG451F'>
                </div>
            </div>
            <div class="desc_form_update">
                <label for="" name="description" require>Description - champ obligatoire</label>
                <input class="input_desc" type="text" name="description" placeholder="L'intervention consiste en...">
            </div>

            <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
                <button type="submit" class="blue-button selection">Ajouter l'intervenant</button>
            </div>
        </form>
    </section>
</body>
</html>


<?php
if ((!empty($_POST['name'])) && !empty($_POST['color']) && !empty($_POST['description'])) {
    $name = htmlspecialchars($_POST['name']);
    $color = htmlspecialchars($_POST['color']);
    $description = htmlspecialchars($_POST['description']);
    insert_intervention_type($con, $name, $color, $description);
}

?>