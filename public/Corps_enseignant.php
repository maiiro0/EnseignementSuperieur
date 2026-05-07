<?php
require_once 'inclus/auth_check.php'; // Vérification de la session
require_once('inclus/Connexion.php'); // Connexion à la base de données
require_once '../database/User_database.php'; // Fonctions liées à la table user
require_once 'inclus/Header.php'; // Header de la page
// Définition de la variable active pour le menu
$active='enseignants';
// Pagination
if (empty($_GET["page"])){ // Vérification de la pagination dans l'URL. Si pas de numéro de page, on considère que c'est la page 1 par défaut
    $page = 1;
    $_GET["page"] = 1;
}
else { // Récupère le numéro de la page dans l'URL
    $page = $_GET['page'];
}


if (empty($_GET['first_name'])) { // Vérification que le filtre du prénom est présent dans l'URL
    $filtre_prenom = '';
    $_GET['first_name'] ="";
}
else { // Récupère le filtre du prénom dans l'URL
    $filtre_prenom = $_GET['first_name'];
}

if (empty($_GET["last_name"])){ // Vérification que le filtre du nom de famille est présent dans l'URL
    $filtre_nom = '';
    $_GET["last_name"] ="";
}
else { // Récupère le filtre du nom de famille dans l'URL
    $filtre_nom = $_GET["last_name"];
}

if (empty($_GET["email"])){ // Vérification que le filtre de l'email est présent dans l'URL
    $filtre_email = '';
    $_GET["email"] ="";
}
else { // Récupère le filtre de l'email dans l'URL
    $filtre_email = $_GET["email"];
}

?>

<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ;?>
    </nav>

    <section class="teaching_staff page">
        <div class="breadcrumb"> <!-- Fil d'ariane -->
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="#">Corps Enseignant</a>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Corps Enseignant</h3>
                <div class="button">
                    <button command="show-modal" commandfor="dialogs" class="blue-button">Ajouter un nouvel Enseignant</button> <!-- Bouton pour accéder à la fenêtre modale d'ajout d'un enseignant -->
                </div>

                <dialog id="dialogs"> <!-- Fenêtre modale d'ajout d'un enseignant -->
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
                            <label for="role_bdd">Role</label> </br> <!-- Champ de saisie du rôle de l'enseignant, ce champ n'est pas obligatoire -->
                            <input type="text" placeholder="Saisissez un rôle de l'enseignant" name="role_bdd" id="role_bdd"></br>
                        </div>
                        
                        <div class="form-width-max">
                            <label for="email_bdd" require>Email</label></br> <!-- Champ de saisie de l'email de l'enseignant, ce champ est obligatoire, l'email doit être au format email -->
                            <input type="text" placeholder="Saisissez l'email de l'enseignant" name="email_bdd" id="email_bdd"></br>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="last_name_bdd" require>Nom</label></br> <!-- Champ de saisie du nom de famille de l'enseignant, ce champ est obligatoire -->
                                <input type="text" placeholder="Saisissez le nom de famille" name="last_name_bdd" id="last_name_bdd"></br>
                            </div>

                            <div>
                                <label for="first_name_bdd" require>Prénom</label></br> <!-- Champ de saisie du prénom de l'enseignant, ce champ est obligatoire -->

                                <input type="text" placeholder="Saisissez le prénom" name="first_name_bdd" id="first_name_bdd"></br>
                            </div>
                        </div>

                        <label for="module_bdd">Modules enseignés - champ obligatoire</label><br> <!-- Champ de sélection des modules enseignés par l'enseignant, ce champ est obligatoire -->
                        <select name="module_bdd[]" id="module_bdd" multiple class="select-multiple-form">
                                <?php
                                    $nom_module = select_name_module($con); // Récupération du nom de tous les modules de la base de données pour les afficher dans la liste déroulante
                                    $nom_module_selected = nom_module_where_instructor($con, $id); // Récupération du nom des modules enseignés par l'enseignant pour les sélectionner par défaut dans la liste déroulante

                                    foreach ($nom_module as $valeurs=>$element) { 
                                        if (in_array($element, $nom_module_selected)) {
                                            echo "<option selected>". $element["name"]."</option>"; // Si le module est enseigné par l'enseignant, on le sélectionne par défaut dans la liste déroulante
                                        }else {
                                            echo "<option>". $element["name"]."</option>"; // Si le module n'est pas enseigné par l'enseignant, on ne le sélectionne pas par défaut dans la liste déroulante
                                        }
                                    }
                                ?>
                        </select>


                        <div class="select-button"> <!-- Boutons de validation ou d'annulation de l'ajout de l'enseignant -->
                            <button type="submit" commandfor="dialogs" command="close" class="grey-button selection">Annuler</button>
                            <button type="submit" class="blue-button selection">Confirmer</button>
                        </div>
                    </form>
                </dialog>

            </div>

            <form method="get" action="">
                <h3 class="yellow">Filtres</h3> <!-- Titre de la section des filtres -->
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
                        <input type="email" name="email" placeholder="Saisissez l'Email">
                    </div>
                    <button class="yellow-button">Filtrer</button>
                </div>
            </form>

            <h4>Enseignements trouvés : </h4>

            <table class="table">
                <thead>
                    <tr class="columns"> <!-- En-tête du tableau avec les noms des colonnes -->
                        <td>Nom de famille</td>
                        <td>Prénom</td>
                        <td>Modules enseignés</td>
                        <td>Nombre d'heure</td>
                        <td></td>
                    </tr>
                </thead>
                <?php
                if (!empty($_GET["first_name"]) || !empty($_GET["last_name"])){ // Vérification que les filtres sont bien dans l'URL, si au moins un des filtres est présent, on affiche les résultats filtrés
                    $filtre_prenom = '%'.$_GET["first_name"].'%';
                    $filtre_nom = '%'.$_GET["last_name"].'%';
                    $filtre_email = '%'.$_GET["email"].'%';

                    $limit = 10;
                    $offset = $page * $limit - $limit; // Pagination

                    if (empty($_GET['first_name'])) { // Vérification que le filtre du prénom est présent dans l'URL, si le filtre du prénom n'est pas présent, on considère que c'est une chaîne de caractères vide pour ne pas filtrer sur le prénom
                        $filtre_prenom = '';
                    }
                    if (empty($_GET["last_name"])){ // Vérification que le filtre du nom de famille est présent dans l'URL, si le filtre du nom de famille n'est pas présent, on considère que c'est une chaîne de caractères vide pour ne pas filtrer sur le nom de famille
                        $filtre_nom = '';
                    }
                    if (empty($_GET["email"])){ // Vérification que le filtre de l'email est présent dans l'URL, si le filtre de l'email n'est pas présent, on considère que c'est une chaîne de caractères vide pour ne pas filtrer sur l'email
                        $filtre_email = '';
                    }


                    $contenu = infos_module_where($con, $filtre_prenom, $filtre_nom, $filtre_email, $offset); // Récupération des informations des enseignants à partir des filtres
                    ?>
                    <tbody>
                        <?php                                        
                        foreach ($contenu as $element => $valeur){
                            echo "<tr>";
                            echo "<td>". $valeur["last_name"]. "</td>"; 
                            echo"<td>". $valeur["first_name"]. "</td>";
                            echo"<td>". $valeur['module']. "</td>";
                            echo"<td>". $valeur['hours_count']. "</td>";
                            ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                            <a href="Fiche_enseignant_informations.php?id=<?php echo $valeur['id']; ?>">Accéder à la fiche</a></td>
                            <?php
                        }
                        echo "</tr>";
                        ?>
                    </tbody>
                    <?php

                    $nb_pages = select_nb_pages_filtre($con, $filtre_prenom, $filtre_nom, $filtre_email);
                    $nb_pages = $nb_pages[0]["nblignes"];
                    $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1; //Pagination
                } 

                else {
                    $limit = 10;
                    $offset = $page * $limit - $limit; //Pagination
                    $contenu = select_infos_table_corps_enseignant($con, $offset);
                    $nom_prenoms_distinct = select_nom_prenom_distinct($con);
                    
                    ?>
                    <tbody>
                        <?php
                        foreach ($contenu as $valeurs=>$element) {
                            echo "<tr>";
                            echo "<td>". $element["last_name"]. "</td>"; 
                            echo"<td>". $element["first_name"]. "</td>";
                            echo"<td>". $element['module']. "</td>";
                            echo"<td>". $element['hours_count']. "</td>";

                            ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                            <a href="Fiche_enseignant_informations.php?id=<?php echo $element['id']; ?>">Accéder à la fiche</a></td>
                            <?php

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    <?php
                    $nb_pages = select_nb_pages_entier($con);
                    $nb_pages = $nb_pages[0]["nblignes"];
                    $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                }
                ?>
            </table>
            <?php
            if ($_GET["page"] == 1 && $nb_pages == 1){ ?> <!-- Si il n'y a qu'une seule page, on n'affiche pas les liens de pagination -->
                <?php
            }
            else if ($_GET["page"] == $nb_pages){?> <!-- Si on est sur la dernière page, on n'affiche que le lien de la page précédente -->
                <a href="Corps_enseignant.php?page=<?php echo $page - 1; ?>&first_name=<?php echo $filtre_prenom; ?>&last_name=<?php echo $filtre_nom; ?>&email=<?php echo $filtre_email; ?>">Page précédente </a><?php
            }
            else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?> <!-- Si on est sur une page intermédiaire, on affiche les liens de la page précédente et de la page suivante -->
                <a href="Corps_enseignant.php?page=<?php echo $page - 1; ?>&first_name=<?php echo $filtre_prenom; ?>&last_name=<?php echo $filtre_nom; ?>&email=<?php echo $filtre_email; ?>">Page précédente </a>
                <a href="Corps_enseignant.php?page=<?php echo $page + 1; ?>&first_name=<?php echo $filtre_prenom; ?>&last_name=<?php echo $filtre_nom; ?>&email=<?php echo $filtre_email; ?>"> Page suivante</a>
                <?php
            } 
            else { ?> <!-- Si on est sur la première page, on n'affiche que le lien de la page suivante -->
                <a href="Corps_enseignant.php?page=<?php echo $page + 1; ?>&first_name=<?php echo $filtre_prenom; ?>&last_name=<?php echo $filtre_nom; ?>&email=<?php echo $filtre_email; ?>"> Page suivante</a><?php
            }
            ?>
        </section>
    </section>
</body>
</html>


<?php 
if (!empty($_POST["role_bdd"]) && !empty($_POST["first_name_bdd"]) && !empty($_POST["last_name_bdd"]) && !empty($_POST["module_bdd"]) && !empty($_POST["email_bdd"])) { 
    // Vérification que les champs du formulaire d'ajout d'un enseignant sont remplis, si tous les champs sont remplis, on ajoute l'enseignant dans la base de données, sinon, l'enseignant n'est pas ajouté et il y a une erreur
    $role = htmlspecialchars($_POST["role_bdd"]);
    $email = htmlspecialchars($_POST["email_bdd"]);
    $first_name = htmlspecialchars($_POST["first_name_bdd"]);
    $last_name = htmlspecialchars($_POST["last_name_bdd"]);
    $module = $_POST["module_bdd"];

    insert_user($con, $role, $email, $last_name, $first_name);
    $id = select_id_user_where($con, $role, $email, $last_name, $first_name);
    insert_instructor($con, $id);
    $id = select_id_instructor($con, $id);
    $id = $id["id"];

    foreach ($module as $modules){ // On prend 1 module enseigné par 1 module enseigné pour les ajouter dans la base de données
        $module_name = select_id_module($con, $modules);
        insert_instructor_module($con, $module_name['id'], $id);
    }
}

?>