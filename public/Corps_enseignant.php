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

    <section class="teaching_staff">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Corps Enseignant</p>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Corps Enseignant</h3>
                <div class="button">
                    <button command="show-modal" commandfor="dialog" class="blue-button">Ajouter un nouvel Enseignant</button>
                </div>

                <dialog id="dialog">
                    <button commandfor="dialog" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Ajouter une Enseignant</h3>
                            <p>Remplissez les informations ci-dessous</p>
                        </div>
                    </div>

                    <form action="" method="post">
                        <div>
                            <label for="title">Titre</label> </br>
                            <input type="text" placeholder="Saisissez un titre sur l'intervention" name="title" id="title"></br>
                        </div>
                        
                        <div class="form-align">
                            <div>
                                <label for="date-start" require>Date de début - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-start" id="date-start"></br>
                            </div>

                            <div>
                                <label for="date-end" require>Date de fin - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-end" id="date-end"></br>
                            </div>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="module">Module - champ obligatoire</label></br>
                                <select name="module" id="module">
                                    <option value="">Sélectionner le module</option>
                                    <?php
                                    require_once "Connexion.php";
                                    $requete = $con->prepare("SELECT id, name FROM module ORDER BY id");
                                    $requete->execute();
                                    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                    foreach ($contenu as $valeurs=>$element) { 
                                        echo "<option>". $element["name"] ."</option>";
                                    }
                                    ?>
                                </select></br>
                            </div>

                            <div>
                                <label for="intervrntion">Type d'intervention - champ obligatoire</label></br>
                                <select name="intervention" id="intervention">
                                    <option value="">Sélectionner le module</option>
                                    <?php 
                                    $requete = $con->prepare("SELECT name FROM intervention_type ORDER BY name");
                                    $requete->execute();
                                    $nom_intervention = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                    foreach ($nom_intervention as $valeurs=>$element) { 
                                        echo "<option>". $element["name"]."</option>";
                                    }
                                    ?>
                                </select></br>
                            </div>
                        </div>

                        <label for="inter">Intervenant - champ obligatoire</label></br>
                        <select name="inter" id="inter">
                                <option value="">Sélectionner des intervenants</option>
                                <?php
                                    $requete = $con->prepare("SELECT upper(last_name), first_name FROM user ORDER BY last_name");
                                    $requete->execute();
                                    $nom_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                    foreach ($nom_intervenants as $valeurs=>$element) { 
                                        echo "<option>". $element["upper(last_name)"]. " ". $element["first_name"] ."</option>";
                                    }
                                ?>
                        </select></br>
                        <div class="select-button">
                            <button commandfor="dialog" command="close" class="grey-button selection">Annuler</button>
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
                if (!empty($_POST["first_name"] && !empty($_POST["last_name"]) && !empty($_POST["email"]))){
                    $filtre_prenom = $_POST["first_name"];
                    $filtre_nom = $_POST["last_name"];
                    $filtre_email = $_POST["email"];

                    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id WHERE u.first_name = :first_name AND u.last_name = :last_name AND u.email = :email");
                    $requete->bindParam(':first_name', $filtre_prenom);
                    $requete->bindParam(':last_name', $filtre_nom);
                    $requete->bindParam(':email', $filtre_email);
                    $requete->execute();
                    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

                    echo "<tr>";
                    echo "<td>". $contenu[0]["last_name"]. "</td>"; 
                    echo"<td>". $contenu[0]["first_name"]. "</td>";
                    echo"<td>". $contenu[0]['module']. "</td>";
                    echo"<td>". $contenu[0]['hours_count']. "</td>";

                    ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                    <a href="">Accéder à la fiche</a></td>
                    <?php

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


