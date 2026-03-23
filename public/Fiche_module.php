<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types Intervention</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <?php require_once('Menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Types intervention</p>
        </div>

    <?php 
    require_once 'Connexion.php';

    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $requete = $con->prepare("SELECT name, description, color FROM intervention_type WHERE id=:id");
        $requete->bindParam(':id', $id);
        $requete->execute();
        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $contenu = $contenu[0];
    }
    ?>
    
    <section class="intervention_sheet">
        <h3>Cours</h3>
        <form action="" method="post">
            <div class="form-align">
                <div>
                    <label for="" name="name" require>Code - Champs obligatoire</label>
                    <input type="text" name="name" value="<?php echo $contenu['code']?>">
                </div>
                <div>
                    <label for="" name="color" require>Nom - champ obligatoire</label>
                    <input type="text" name="color" value="<?php echo $contenu['name']?>">
                </div>
            </div>
            <div class="desc_form_update">
                <label for="" name="description" require>Nombre d'heures - champ obligatoire</label>
                <input class="input_desc" type="text" name="description" value="<?php echo $contenu['hours']?>">
            </div>
             <div class="desc_form_update">
                <label for="" name="description" require>Parent - champ obligatoire</label>
                <input class="input_desc" type="text" name="description" value="<?php echo $contenu['parent']?>">
            </div>

            <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
            </div>
        </form>

        <button command="show-modal" commandfor="dialog" class="red-button selection">Supprimer</button>
        <dialog id="dialog">
            <button commandfor="dialog" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button>
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
                        <button class="grey-button selection" commandfor="dialog" command="close">Annuler</button>
                        <button class="red-button selection" type="submit" name="action" value="confirm-delete">Confirmer</button>  
                    </div>
                </form>
            </div>
        </dialog>
        <button type="submit" class="blue-button selection">Enregistrer les informations</button>
    </section>
</body>
</html>

<?php
if (isset($_POST['action']) && $_POST['action'] === 'confirm-delete') {
    $requete = $con->prepare("SELECT id FROM module WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $multi_id = $requete->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($multi_id as $valeurs){
        $requete = $con->prepare("DELETE FROM instructor_module WHERE instructor_id = :multi_id");
        $requete->bindParam(':multi_id', $valeurs["id"]);
        $requete->execute();
    }

    $requete = $con->prepare("DELETE FROM module WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();

    $requete = $con->prepare("DELETE FROM module WHERE id = :id");
    var_dump('Oui');
    $requete->bindParam(':id', $id);
    $requete->execute();
}


if ((!empty($_POST['code'])) && !empty($_POST['name']) && !empty($_POST['hours']) && !empty($_POST['parent']) && !isset($_POST['description'])) {
    $code = htmlspecialchars($_POST['code']);
    $name = htmlspecialchars($_POST['name']);
    $hours = htmlspecialchars($_POST['hours']);
    $parent = htmlspecialchars($_POST['parent']);
    $description = htmlspecialchars($_POST['description']);

    $requete = $con->prepare("UPDATE module SET code = :code, name = :name, hours = :hours, parent = :parent, description = :description WHERE id=:id");   

    $requete->bindParam(':id', $id);
    $requete->bindParam(':code', $code);
    $requete->bindParam(':name', $name);
    $requete->bindParam(':hours', $hours);
    $requete->bindParam(':parent', $parent);
    $requete->bindParam(':description', $description);
    $requete->execute();
}
?>