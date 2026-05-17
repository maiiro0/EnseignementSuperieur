
<?php 
require_once 'inclus/auth_check.php';
require_once 'inclus/connexion.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
$active='enseignants';

if (isset($_GET['id'])) { // Vérification que l'id de l'enseignant est présent dans l'URL pour pouvoir afficher les informations de l'enseignant, si l'id n'est pas présent, les variables utilisées pour afficher les informations de l'enseignant ne seront pas définies et il y aura une erreur
    $id = htmlspecialchars($_GET['id']);
    $infos = select_infos_enseignant($con, $id); // Récupération des informations de l'enseignant à partir de son id, cette fonction est définie dans le fichier database/User_database.php
}

if ((!empty($_POST['last_name'])) && !empty($_POST['first_name']) && !empty($_POST['email']) && !empty($_POST['name'])) { // Vérification que les champs obligatoires du formulaire sont remplis pour pouvoir enregistrer les modifications des informations de l'enseignant, si au moins un des champs obligatoires n'est pas rempli, les informations ne seront pas enregistrées et il y aura une erreur
    $last_name = htmlspecialchars($_POST['last_name']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $email = htmlspecialchars($_POST['email']);
    $name = $_POST['name'];
    update_infos_enseignant($con, $id, $last_name, $first_name, $email, $name); // Mise à jour des informations de l'enseignant dans la base de données à partir des informations saisies dans le formulaire, cette fonction est définie dans le fichier database/User_database.php
    header("Location: fiche_enseignant_informations.php?id=" . $id); // Redirection vers la même page pour afficher les informations mises à jour de l'enseignant, l'id de l'enseignant est passé en paramètre dans l'URL pour pouvoir afficher les informations de l'enseignant
    exit();
}


?>

<body>
    <nav>
        <?php include_once 'inclus/menu_gestion_licence.php' ?>
    </nav>
    <section class="teacher-information page">  
        <div class="breadcrumb">  <!-- Fil d'ariane -->
            <a href="calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="corps_enseignant.php">Corps enseignant</a>
            <p>></p>
            <a href="#"><span><?php echo $infos["first_name"];?></span> <span><?php echo $infos["last_name"];?></span></a> <!-- Le nom et le prénom de l'enseignant sont affichés dans le fil d'ariane pour indiquer que c'est la page de cet enseignant, les informations de l'enseignant sont récupérées à partir de son id qui est passé en paramètre dans l'URL -->
            <p>></p>
            <a href="#">Informations générales</a>
        </div>

        <section>
            <div class="align margin-null">
                <h3 class="margin-null"><span><?php echo $infos["first_name"] ?></span> <!-- Affichage du prénom de l'enseignant dans le titre de la page -->
                <span><?php echo $infos["last_name"] ?></span></h3>
            </div>
            <p class="yellow-title">Modules enseignés</p>
            <div class="information-part">
            <?php
                
                $contenu = select_infos_modules_enseignant($con, $id); // Récupération des modules enseignés par l'enseignant à partir de son id, cette fonction est définie dans le fichier database/User_database.php, les informations récupérées sont le nom du module et le nombre d'heures associées à ce module

                foreach ($contenu as $colonne => $element) { // On prend 1 module enseigné par 1 module enseigné pour les afficher, les informations de chaque module sont affichées à la suite les unes des autres
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
                <a href="fiche_enseignant_interventions.php?id=<?php echo $id; ?>"class="link-unselected">Interventions</a> <!-- Lien vers la page des interventions de l'enseignant, l'id de l'enseignant est passé en paramètre dans l'URL pour pouvoir afficher les interventions de cet enseignant -->
            </div>
            <div>
                <p class="yellow-title">Informations générales</p>
            </div>
            <form action="" method="post" class= "teacher-information-form">
                <div class="form-gap">
                    <div>
                        <label for="last_name">Nom de famille - champ obligatoire</label> </br> <!-- Champ de saisie du nom de famille de l'enseignant, ce champ est obligatoire, le nom de famille -->
                        <input class="form-input" type="text" value="<?php echo $infos["last_name"] ?>" name="last_name" id="last_name"></br>
                    </div>
                    <div>
                        <label for="first_name">Prénom - champ obligatoire</label> </br> <!-- Champ de saisie du prénom de l'enseignant, ce champ est obligatoire -->
                        <input class="form-input" type="text" value="<?php echo $infos["first_name"] ?>" name="first_name" id="first_name"></br>
                    </div>
                    <div>
                        <label for="email">Email - champ obligatoire</label> </br> <!-- Champ de saisie de l'email de l'enseignant, ce champ est obligatoire, l'email doit être au format email pour être utilisé dans la page de calendrier pour contacter l'enseignant -->
                        <input class="form-input" type="email" value="<?php echo $infos["email"] ?>" name="email" id="email"></br>
                    </div>   
                </div>
                <div>
                    <label for="name">Modules enseignés - champ obligatoire  - Ctrl pour sélectionner plusieurs intervenants</label><br> <!-- Champ de sélection des modules enseignés par l'enseignant, ce champ est obligatoire -->
                    <select name="name[]" id="name" multiple class="select-multiple">
                            <?php
                                $nom_module = select_modules_corp_enseignant($con); // Récupération de tous les modules enseignés par le corps enseignant à partir de la base de données, cette fonction est définie dans le fichier database/User_database.php, les informations récupérées sont le nom du module et le nombre d'heures associées à ce module
                                $nom_module_selected = select_modules_enseignées($con, $id); // Récupération des modules enseignés par l'enseignant à partir de son id, cette fonction est définie dans le fichier database/User_database.php, les informations récupérées sont le nom du module et le nombre d'heures associées à ce module
                                foreach ($nom_module as $valeurs=>$element) { // On prend 1 module par 1 module pour les afficher dans la liste de sélection, les informations de chaque module sont affichées à la suite les unes des autres, si le module est enseigné par l'enseignant, il est sélectionné par défaut dans la liste de sélection
                                    if (in_array($element, $nom_module_selected)) { // Vérification que le module est enseigné par l'enseignant, si c'est le cas, on sélectionne ce module par défaut dans la liste de sélection
                                        echo "<option selected>". $element["name"]."</option>";
                                    }else {
                                        echo "<option>". $element["name"]."</option>"; // Sinon, on affiche le module sans le sélectionner par défaut dans la liste de sélection
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
