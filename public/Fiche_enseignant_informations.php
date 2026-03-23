
<?php 
require_once 'Connexion.php';

/*
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=1");
    // $requete->bindParam(':id', $id);
    $requete->execute();
    $infos = $requete ->fetchAll(\PDO::FETCH_ASSOC);
}
*/
$id =11;
$requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=:id");
$requete->bindParam(':id', $id);
$requete->execute();
$infos = $requete->fetch(PDO::FETCH_ASSOC);

?>

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
        <?php include_once 'Menu_gestion_licence.php' ?>
    </nav>
    <section class="teacher-information page">
        <div class="breadcrumb">  
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Corps enseignant</p>
            <p>></p>
            <p><?php echo $infos["first_name"] ?></p>
            <p><?php echo $infos["last_name"] ?></p>
            <p>></p>
            <p>Informations générales</p>
        </div>

        <section>
            <div class="align margin-null">
                <h3 class="margin-null"><span><?php echo $infos["first_name"] ?></span>
                <span><?php echo $infos["last_name"] ?></span></h3>
            </div>
            <p class="yellow-title">Modules enseignés</p>
            <div class="information-part">
            <?php
                $requete = $con->prepare("SELECT m.name, m.hours_count FROM instructor_module im JOIN module m ON im.module_id = m.id  WHERE im.instructor_id= :id ");
                $requete->bindParam(':id', $id);
                $requete->execute();
                $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($contenu as $colonne => $element) {
                    echo"<p>";
                    echo "<span>". $element["name"]. "</span>"; ?>
                    <span class="padding-5">:</span>
                    <?php
                    echo"<span>". $element['hours_count']. "</span>";
                    echo"<span>"."h00". "</span>" ;
                    echo"</p>";
                }
            ?>  
            </div>
        </section>
        <section class="form-part">
            <div class="link-part">
                <a href="" class="link-select">Informations générales</a>
                <a href=""  class="link-unselected">Interventions</a>
            </div>
            <div>
                <p class="yellow-title">Informations générales</p>
            </div>
            <form action="" method="post" class= "teacher-information-form">
                <div class="form-gap">
                    <div>
                        <label for="last_name">Nom de famille - champ obligatoire</label> </br>
                        <input class="form-input" type="text" placeholder=" <?php echo $infos["last_name"] ?>" name="last_name" id="last_name"></br>
                    </div>
                    <div>
                        <label for="first_name">Prénom - champ obligatoire</label> </br>
                        <input class="form-input" type="text" placeholder=" <?php echo $infos["first_name"] ?>" name="first_name" id="first_name"></br>
                    </div>
                    <div>
                        <label for="email">Email - champ obligatoire</label> </br>
                        <input class="form-input" type="text" placeholder=" <?php echo $infos["email"] ?>" name="email" id="email"></br>
                    </div>   
                </div>
                <div>
                    <label for="name">Modules enseignés - champ obligatoire</label><br>
                    <select name="name[]" id="name" multiple class="select-multiple">
                            <?php
                                $requete = $con->prepare("SELECT m.name FROM  module m;");
                                $requete->execute();
                                $nom_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                foreach ($nom_intervenants as $valeurs=>$element) { 
                                    echo "<option selected>". $element["name"]."</option>";
                                }
                            ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="blue-button modification-button">Enregistrer les informations</button>
                </div>
            </form>
        </section>
    </section>
</body>
</html>

<?php
if ((!empty($_POST['last_name'])) && !empty($_POST['first_name']) && !empty($_POST['email'])) {
    $last_name = htmlspecialchars($_POST['last_name']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $email = htmlspecialchars($_POST['email']);

    $requete = $con->prepare("UPDATE user SET last_name = :last_name, first_name = :first_name, email = :email WHERE id=:id");
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':id', $id);
    $requete->execute();

    if(!empty($_POST['name'])){
        $name = $_POST['name'];
        $requete = $con->prepare("DELETE FROM instructor_module WHERE instructor_id = :id");
        $requete->bindParam(':id', $id);
        $requete->execute();
    
        foreach ($name as $colonne => $element) {
            $requete = $con->prepare("SELECT id FROM module WHERE name = :element");
            $requete->bindParam(':element', $element);
            $requete->execute();
            $module_id = $requete->fetch(PDO::FETCH_ASSOC);

            $requete = $con->prepare("INSERT INTO instructor_module VALUES (:id ,:module_id)");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':module_id', $module_id['id']);
            $requete->execute();
        }

    }
}

?>