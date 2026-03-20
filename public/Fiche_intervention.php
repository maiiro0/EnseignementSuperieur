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
        // en cours par Chloé
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
                    <label for="" name="name" require>Nom - champ obligatoire</label>
                    <input type="text" name="name" value="<?php echo $contenu['name']?>">
                </div>
                <div>
                    <label for="" name="color" require>Code couleur (hexadécimal) - champ obligatoire</label>
                    <input type="text" name="color" value="<?php echo $contenu['color']?>">
                </div>
            </div>
            <div>
                <label for="" name="description" require>Description - champ obligatoire</label>
                <input type="text" name="description" value="<?php echo $contenu['description']?>">
            </div>
        </div>
        <div>
            <label for="" name="description" require>Description - champ obligatoire</label>
            <input type="text" name="description" value="<?php echo $contenu['description']?>">
        </div>

        <button action="Type_intervention.php" class="grey-button selection">Retour à la liste</button>
        <button type="submit" class="red-button selection" value="remove">Supprimer</button>
        <button type="submit" class="blue-button selection" value="add">Enregistrer les informations</button>
    </form>



            <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
                <button class="red-button selection">Supprimer</button>
                <button type="submit" class="blue-button selection">Enregistrer les informations</button>
            </div>
        </form>
    </section>
</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] === "POST"){
    if ($_POST['action'] === 'remove') {
        $requete = $con->prepare("DELETE FROM intervention_type WHERE id = :id");
        $requete -> bindParam('id', $id);
    }

    elseif ($_POST['action'] === 'add'){
        if ((!empty($_POST['name'])) && !empty($_POST['color']) && !empty($_POST['description'])) {
            $name = htmlspecialchars($_POST['name']);
            $color = htmlspecialchars($_POST['color']);
            $description = htmlspecialchars($_POST['description']);

            $requete = $con->prepare("UPDATE intervention_type SET name = :name, color = :color, description = :description WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':name', $name);
            $requete->bindParam(':color', $color);
            $requete->bindParam(':description', $description);
            $requete->execute();
        }
    }
}