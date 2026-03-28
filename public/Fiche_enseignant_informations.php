
<?php 
require_once 'inclus/Connexion.php';
require_once 'inclus/Header.php';

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $infos = $requete->fetch(PDO::FETCH_ASSOC);
}

?>

<body>
    <nav>
        <?php include_once 'inclus/Menu_gestion_licence.php' ?>
    </nav>
    <section class="teacher-information page">  
        <div class="breadcrumb">  
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Corps_enseignant.php">Corps enseignant</a>
            <p>></p>
            <a href="#"><span><?php echo $infos["first_name"];?></span> <span><?php echo $infos["last_name"];?></span></a>
            <p>></p>
            <a href="#">Informations générales</a>
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
                <a href="#" class="link-select">Informations générales</a>
                <a href="Fiche_enseignant_interventions.php?id=<?php echo $id; ?>"class="link-unselected">Interventions</a>
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
                                $nom_module = $requete->fetchAll(\PDO::FETCH_ASSOC);

                                $requete = $con->prepare("SELECT m.name FROM  module m JOIN instructor_module im ON m.id = im.module_id WHERE im.instructor_id = :id");
                                $requete->bindParam(':id', $id);
                                $requete->execute();
                                $nom_module_selected = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                foreach ($nom_module as $valeurs=>$element) { 
                                    if (in_array($element, $nom_module_selected)) {
                                        echo "<option selected>". $element["name"]."</option>";
                                    }else {
                                        echo "<option>". $element["name"]."</option>";
                                    }
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