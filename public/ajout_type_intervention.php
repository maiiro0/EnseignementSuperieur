<?php
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
require_once 'inclus/connexion.php';
$active='types';?>

<body>
    <nav>
        <?php require_once('inclus/menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <a href="calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="type_intervention.php">Types d'intervention</a> <!-- Fil d'ariane -->
            <p>></p>
            <a href="#">Ajouter type intervention</a>
        </div>
    
    <section class="intervention_sheet"> 
        <h3>Intervention</h3>
        <form action="" method="post"> <!-- Formulaire d'ajout d'un type d'intervention, les données sont envoyées en POST pour être traitées à la fin du fichier -->
            <div class="form-align">
                <div>
                    <label for="" name="name" require>Nom - champ obligatoire</label> <!-- Champ de saisie du nom du type d'intervention, ce champ est obligatoire -->
                    <input type="text" name="name" placeholder='Saisir un nom'>
                </div>
                <div>
                    <label for="" name="color" require>Code couleur (hexadécimal) - champ obligatoire</label> <!-- Champ de saisie du code couleur du type d'intervention, ce champ est obligatoire, le code couleur doit être au format hexadécimal pour être utilisé dans la page de calendrier pour différencier les types d'intervention par leur couleur -->
                    <input type="text" name="color" placeholder='#DG451F'>
                </div>
            </div>
            <div class="desc_form_update">
                <label for="" name="description" require>Description - champ obligatoire</label> <!-- Champ de saisie de la description du type d'intervention, ce champ est obligatoire, la description est utilisée pour donner plus d'informations sur le type d'intervention dans la page de calendrier -->
                <input class="input_desc" type="text" name="description" placeholder="L'intervention consiste en...">
            </div>

            <div class="button-intervention">
                <a href="type_intervention.php" class="grey-button selection">Retour à la liste</a>
                <button type="submit" class="blue-button selection">Ajouter l'intervenant</button>
            </div>
        </form>
    </section>
</body>
</html>


<?php
if ((!empty($_POST['name'])) && !empty($_POST['color']) && !empty($_POST['description'])) { // Vérification des champs obligatoires
    if ($_POST['color'][0] == '#'){ // Vérification que le code couleur commence par un # pour être au format hexadécimal
        $name = htmlspecialchars($_POST['name']);
        $color = htmlspecialchars($_POST['color']);
        $description = htmlspecialchars($_POST['description']);
        insert_intervention_type($con, $name, $color, $description); // Insertion du nouveau type d'intervention dans la base de données à partir des informations saisies dans le formulaire, cette fonction est définie dans le fichier database/User_database.php
    }
    else { // Sinon, message d'erreur 
        echo "<p>"."Vous n'avez pas mis d'hexadecimal"."</p>"; 
    }
}
else if (isset($_POST['name']) || isset($_POST['color']) || isset($_POST['description'])) { // Si au moins un des champs obligatoires est rempli, mais pas tous, message d'erreur
    echo "<p>"."Erreur de saisie"."</p>";
}

?>
