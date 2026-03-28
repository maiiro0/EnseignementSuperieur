<?php
require_once 'inclus/Header.php';

if (empty($_GET["page"])){
    $page = 1;
    $_GET["page"] = 1;
}

else {
    $page = $_GET['page'];
}
?>

<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); 
        require_once('inclus/Connexion.php');
        require_once '../database/User_database.php';?>
    </nav>

    <section class="teaching_staff page">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <a href="#">Corps Enseignant</a>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Corps Enseignant</h3>
                <div class="button">
                    <button command="show-modal" commandfor="dialogs" class="blue-button">Ajouter un nouvel Enseignant</button>
                </div>

                <dialog id="dialogs">
                    <button commandfor="dialogs" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Ajouter un Enseignant</h3>
                            <p>Remplissez les informations ci-dessous</p>
                        </div>
                    </div>

                    <form action="" method="post">
                        <div class="form-width-max">
                            <label for="role_bdd">Role</label> </br>
                            <input type="text" placeholder="Saisissez un rôle de l'enseignant" name="role_bdd" id="role_bdd"></br>
                        </div>
                        
                        <div class="form-width-max">
                            <label for="email_bdd" require>Email</label></br>
                            <input type="text" name="email_bdd" id="email_bdd"></br>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="last_name_bdd" require>Nom</label></br>
                                <input type="text" name="last_name_bdd" id="last_name_bdd"></br>
                            </div>

                            <div>
                                <label for="first_name_bdd" require>Prénom</label></br>
                                <input type="text" name="first_name_bdd" id="first_name_bdd"></br>
                            </div>
                        </div>

                        <label for="module_bdd">Modules enseignés - champ obligatoire</label><br>
                        <select name="module_bdd[]" id="module_bdd" multiple class="select-multiple-form">
                                <?php
                                    $nom_module = select_name_module($con);
                                    $nom_module_selected = nom_module_where_instructor($con, $id);

                                    foreach ($nom_module as $valeurs=>$element) { 
                                        if (in_array($element, $nom_module_selected)) {
                                            echo "<option selected>". $element["name"]."</option>";
                                        }else {
                                            echo "<option>". $element["name"]."</option>";
                                        }
                                    }
                                ?>
                        </select>


                        <div class="select-button">
                            <button type="submit" commandfor="dialogs" command="close" class="grey-button selection">Annuler</button>
                            <button type="submit" class="blue-button selection">Confirmer</button>
                        </div>
                    </form>
                </dialog>

            </div>

            <form method="post" action="">
                <h3 class="yellow">Filtre</h3>
                <div class="filter-row">
                    <div class="filter-column">
                        <label name="last_name">Nom de famille</label>
                        <input type="text" name="last_name" placeholder="Saisissez le nom de famille">
                    </div>
                    <div class="filter-column">
                        <label name="first_name">Prénom</label>
                        <input type="text" name="first_name" placeholder="Saisissez le prénom">
                    </div>
                    <div class="filter-column">
                        <label name="email">Email</label>
                        <input type="email" name="email">
                    </div>
                    <button class="yellow-button">Filtrer</button>
                </div>
            </form>

            <h4>Enseignements trouvés : </h4>

            <table class="table">
                <tr class="columns">
                    <td>Nom de famille</td>
                    <td>Prénom</td>
                    <td>Modules enseignés</td>
                    <td>Nombre d'heure</td>
                    <td></td>
                </tr>
                <?php
                if (!empty($_POST["first_name"]) || !empty($_POST["last_name"]) || !empty($_POST["email"])){
                    $filtre_prenom = '%'.$_POST["first_name"].'%';
                    $filtre_nom = '%'.$_POST["last_name"].'%';
                    $filtre_email = '%'.$_POST["email"].'%';

                    $limit = 10;
                    $offset = $page + $limit - $limit;

                    if (empty($_POST['first_name'])) {
                        $filtre_prenom = '';
                    }
                    if (empty($_POST["last_name"])){
                        $filtre_nom = '';
                    }
                    if (empty($_POST["email"])){
                        $filtre_email = '';
                    }

                    $contenu = infos_module_where($con, $filtre_prenom, $filtre_nom, $filtre_email, $offset);
                    foreach ($contenu as $element => $valeur){
                        echo "<tr>";
                        echo "<td>". $valeur["last_name"]. "</td>"; 
                        echo"<td>". $valeur["first_name"]. "</td>";
                        echo"<td>". $valeur['module']. "</td>";
                        echo"<td>". $valeur['hours_count']. "</td>";
                        ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                        <a href="">Accéder à la fiche</a></td>
                        <?php
                    }
                    echo "</tr>";


                    $nb_pages = select_nb_pages_filtre($con, $filtre_prenom, $filtre_nom, $filtre_email);
                    $nb_pages = $nb_pages[0]["nblignes"];
                    $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                } 

                else {
                    $limit = 10;
                    $offset = $page * $limit - $limit;
                    $contenu = select_infos_table_corps_enseignant($con, $offset);
                    foreach ($contenu as $valeurs=>$element) {
                        echo "<tr>";
                        echo "<td>". $element["last_name"]. "</td>"; 
                        echo"<td>". $element["first_name"]. "</td>";
                        echo"<td>". $element['module']. "</td>";
                        echo"<td>". $element['hours_count']. "</td>";

                        ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                        <a href="">Accéder à la fiche</a></td>
                        <?php

                        echo "</tr>";
                    }

                    $nb_pages = select_nb_pages_entier($con);
                    $nb_pages = $nb_pages[0]["nblignes"];
                    $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                }

                if ($_GET["page"] == 1 && $nb_pages == 1){ ?>
                    <?php
                }
                else if ($_GET["page"] == $nb_pages){?>
                    <a href="Corps_enseignant.php?page=<?php echo $page - 1; ?>">Page précédente </a><?php
                }
                else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?>
                    <a href="Corps_enseignant.php?page=<?php echo $page - 1; ?>">Page précédente </a>
                    <a href="Corps_enseignant.php?page=<?php echo $page + 1; ?>"> Page suivante</a>
                    <?php
                } 
                else { ?>
                    <a href="Corps_enseignant.php?page=<?php echo $page + 1; ?>"> Page suivante</a><?php
                }

                ?>

            </table>
        </section>
    </section>
</body>
</html>


<?php 
if (!empty($_POST["role_bdd"]) && !empty($_POST["first_name_bdd"]) && !empty($_POST["last_name_bdd"]) && !empty($_POST["module_bdd[]"]) && !empty($_POST["email_bdd"])) {
    $role = htmlspecialchars($_POST["role_bdd"]);
    $email = htmlspecialchars($_POST["email_bdd"]);
    $first_name = htmlspecialchars($_POST["first_name_bdd"]);
    $last_name = htmlspecialchars($_POST["last_name_bdd"]);
    $module = htmlspecialchars($_POST["module_bdd[]"]);

    insert_user($con, $role, $email, $last_name, $first_name);
    $id = select_id_user_where($con, $role, $email, $last_name, $first_name);
    insert_instructor($con, $id);
    $id = select_id_instructor($con, $id);

    foreach ($module as $modules){
        $module_name = select_id_module($con, $modules);
        insert_instructor_module($con, $module_name);
    }
}

?>