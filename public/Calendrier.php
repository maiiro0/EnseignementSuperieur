<?php
require_once 'header.php'?>

<body>
    <nav>
        <?php require_once("Menu_gestion_licence.php"); ?>
    </nav>

    <section class="calendar page">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Calendrier</p>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Calendrier</h3>
                <div class="button">
                    <button command="show-modal" commandfor="dialog" class="blue-button">Ajouter une nouvelle intervention</button>
                </div>

                <dialog id="dialog">
                    <button commandfor="dialog" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Ajouter une intervention</h3>
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
            <h4>Interventions de la semaine</h4>

            <table class="table">
                <tr class="columns">
                    <td>Dates de l'intervention</td>
                    <td>Modules & titre</td>
                    <td>Type</td>
                    <td>Intervenants</td>
                    <td>En visio</td>
                    <td></td>
                </tr>
                <?php
                    $requete = $con->prepare("SELECT id, start_date, end_date, intervention_type_id, module_id, remotely FROM course");
                    $requete->execute();
                    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($contenu as $valeurs=>$element) {
                        echo "<tr>";
                        echo "<td>". $element["start_date"]. "</td>"; //Colonne Date de début. Il manque à mettre l'heure de fin

                        $module_id = $element["module_id"];
                        $requete = $con->prepare("SELECT name FROM module WHERE id = :module_id");
                        $requete -> bindParam(':module_id', $module_id); 
                        $requete->execute();
                        $nom_module = $requete->fetch(\PDO::FETCH_ASSOC); // On va chercher le nom du module dans une autre table
                        echo "<td>". $nom_module["name"] . "</td>";

                        $type_intervention = $element["intervention_type_id"];
                        $requete = $con->prepare("SELECT name FROM intervention_type WHERE id = :type_intervention");
                        $requete -> bindParam(':type_intervention', $type_intervention);
                        $requete->execute();
                        $nom_intervention = $requete->fetch(\PDO::FETCH_ASSOC); //On va chercher le nom de l'intervention dans la table intervention_type
                        echo "<td>". $nom_intervention["name"] ."</td>";

                        $id = $element["id"];
                        $requete = $con->prepare("SELECT upper(u.last_name), upper(u.first_name) FROM user u WHERE u.id IN (SELECT i.user_id FROM instructor i WHERE i.id in (SELECT c.instructor_id FROM course_instructor c WHERE c.course_id = :id))");
                        $requete -> bindParam(':id', $id); 
                        $requete->execute();
                        $noms_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC); //On récupère les noms et les prénoms en majuscule
                        echo "<td>";
                        $temporaire = "";
                        foreach ($noms_intervenants as $colonne=>$noms){
                            $temporaire .= ", ". $noms["upper(u.first_name)"][0].". ".$noms["upper(u.last_name)"];
                        }
                        echo substr($temporaire, 2); //substr permet de récupérer à partir d'un certain endroit de la chaîne de caractère. Ici à partir de l'élément en place 2
                        echo "</td>";


                        if ($element["remotely"] == 0){
                            ?><td> <img src="assets/VisioOff.png" alt=""> </td><?php
                        }   
                        else {
                            ?><td> <img src="assets/VisioOn.png" alt=""> </td><?php
                        }

                        ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                        <a href="">Accéder à la fiche</a></td>
                        <?php

                        echo "</tr>";
                    }
                ?>

            </table>
        </section>
    </section>
</body>
</html>


<?php
if ((!empty($_POST['title'])) && !empty($_POST['date-start']) && !empty($_POST['date-end']) && !empty($_POST['module']) && !empty($_POST['intervention']) && !empty($_POST['inter'])){
    var_dump("Déjà ça c'est fait");
    $title = htmlspecialchars($_POST['title']);
    $date_start = htmlspecialchars($_POST['date-start']);
    $date_end = htmlspecialchars($_POST['date-end']);
    $module = htmlspecialchars($_POST['module']);
    $intervention = htmlspecialchars($_POST['intervention']);
    $intervenants = htmlspecialchars($_POST['inter']); //Ne pas oublier : intervenants peut contenir plusieurs intervenants

    $requete = $con->prepare('SELECT id FROM intervention_type WHERE name = :intervention');
    $requete->bindParam(':intervention', $intervention);
    $requete->execute();
    $id_intervention = $requete->fetchAll(\PDO::FETCH_ASSOC); //Récupère l'ID de l'intervention
    
    $requete = $con->prepare('SELECT id FROM module WHERE name = :module');
    $requete->bindParam(':module', $module);
    $requete->execute();
    $id_module = $requete->fetchAll(\PDO::FETCH_ASSOC); //Récupère l'ID du module

    $date_start = new \DateTime($date_start);
    $date_end = new \DateTime($date_end);

    $delais = $date_start->diff($date_end);
    var_dump($delais);


}