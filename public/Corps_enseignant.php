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
        <?php require_once('Menu_gestion_licence.php'); 
        require_once('Connexion.php')?>
    </nav>

    <section class="teaching_staff page">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Corps Enseignant</p>
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

                    if (empty($_POST['first_name'])) {
                        $filtre_prenom = '';
                    }
                    if (empty($_POST["last_name"])){
                        $filtre_nom = '';
                    }
                    if (empty($_POST["email"])){
                        $filtre_email = '';
                    }

                    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id WHERE u.first_name LIKE :first_name OR u.last_name LIKE :last_name OR u.email LIKE :email");
                    $requete->bindParam(':first_name', $filtre_prenom);
                    $requete->bindParam(':last_name', $filtre_nom);
                    $requete->bindParam(':email', $filtre_email);
                    $requete->execute();
                    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

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
                } 

                else {
                    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id");
                    $requete->execute();
                    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

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

    $requete = $con->prepare("INSERT INTO user (role, email, last_name, first_name) VALUES (:role, :email, :last_name, :first_name)");
    $requete->bindParam(':role', $role);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->execute();

    $requete = $con->prepare("SELECT id FROM user WHERE role = :role AND email=:email AND last_name=:last_name AND first_name=:first_name");
    $requete->bindParam(':role', $role);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->execute();
    $id = $requete->fetchAll(\PDO::FETCH_ASSOC);

    var_dump($id[0]["id"]);

    $requete = $con->prepare("INSERT INTO instructor (user_id) VALUES (:id)");
    $requete ->bindParam(':id', $id[0]["id"]);
    $requete->execute();

    $requete = $con->prepare("SELECT id FROM instructor WHERE user_id = :user");
    $requete->bindParam(':user', $id[0]["id"]);
    $requete->execute();
    $id = $requete->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($module as $modules){
        $requete = $con->prepare("SELECT id FROM module WHERE name=:name");
        $requete->bindParam(':name', $modules);
        $requete->execute();
        $module_name = $requete->fetch(\PDO::FETCH_ASSOC);

        $requete = $con->prepare("INSERT INTO instructor_module (instructor_id, module_id) VALUES (:instructor_id, :module_id)");
        $requete->bindParam(':instructor_id', $id);
        $requete->bindParam(':module_id', $module_name);
        $requete->execute();
    }
}

?>