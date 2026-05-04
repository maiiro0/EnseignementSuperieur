
<?php 
require_once 'inclus/auth_check.php';
require_once 'inclus/Connexion.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
$active='enseignants';

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $infos = select_infos_enseignant($con, $id);
}

if ((!empty($_POST['last_name'])) && !empty($_POST['first_name']) && !empty($_POST['email']) && !empty($_POST['name'])) {
    $last_name = htmlspecialchars($_POST['last_name']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $email = htmlspecialchars($_POST['email']);
    $name = $_POST['name'];
    update_infos_enseignant($con, $id, $last_name, $first_name, $email, $name);
    header("Location: Fiche_enseignant_informations.php?id=" . $id);
    exit();
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
                
                $contenu = select_infos_modules_enseignant($con, $id);

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
                        <input class="form-input" type="text" value="<?php echo $infos["last_name"] ?>" name="last_name" id="last_name"></br>
                    </div>
                    <div>
                        <label for="first_name">Prénom - champ obligatoire</label> </br>
                        <input class="form-input" type="text" value="<?php echo $infos["first_name"] ?>" name="first_name" id="first_name"></br>
                    </div>
                    <div>
                        <label for="email">Email - champ obligatoire</label> </br>
                        <input class="form-input" type="email" value="<?php echo $infos["email"] ?>" name="email" id="email"></br>
                    </div>   
                </div>
                <div>
                    <label for="name">Modules enseignés - champ obligatoire  - Ctrl pour sélectionner plusieurs intervenants</label><br>
                    <select name="name[]" id="name" multiple class="select-multiple">
                            <?php
                                $nom_module = select_modules_corp_enseignant($con);
                                $nom_module_selected = select_modules_enseignées($con, $id);
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
