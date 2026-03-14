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

    <section class="calendar">
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
                            <p>Remplissez les informationd ci-dessous</p>
                        </div>
                    </div>

                    <form action="" method="post">
                        <div>
                            <label for="title">Titre</label> </br>
                            <input type="text" placeholder="Saisissez un titre sur l'intervention" name="title"></br>
                        </div>
                        
                        <div class="form-align">
                            <div>
                                <label for="date-start" require>Date de début - champ obligatoire</label></br>
                                <input type="date" name="date-start"></br>
                            </div>

                            <div>
                                <label for="date-end" require>Date de fin - champ obligatoire</label></br>
                                <input type="date" name="date-end"></br>
                            </div>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="module">Module - champ obligatoire</label></br>
                                <select name="Sélectionner le module" name="module">
                                    <option value="">Sélectionner le module</option>
                                    <option value="1">1</option>
                                    <option value="1">1</option>
                                </select></br>
                            </div>

                            <div>
                                <label for="module">Type d'intervention - champ obligatoire</label></br>
                                <select name="Sélectionner le module" name="module">
                                    <option value="">Sélectionner le module</option>
                                    <option value="1">1</option>
                                    <option value="1">1</option>
                                </select></br>
                            </div>
                        </div>

                        <label for="inter">Intervenant - champ obligatoire</label></br>
                        <select name="Sélectionner des intervenants" name="inter">
                                <option value="1">Sonia ARACIL</option>
                                <option value="1">Olivier SALESSE</option>
                        </select></br>
                    </form>

                    <div>
                        <button commandfor="dialog" command="close" class="blue-button">Fermer</button>
                        <button commandfor="dialog" command="close" class="blue-button">Fermer</button>
                    </div>
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
                    include "Connexion.php";

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