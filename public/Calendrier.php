<?php
require_once 'inclus/auth_check.php';
require_once('inclus/Connexion.php');
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
$active = 'calendrier';



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
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

    <section class="calendar page">
        <div class="breadcrumb">
            <a href="#"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="#">Calendrier</a>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Calendrier</h3>
                <div class="button">
                    <button type="button" command="show-modal" commandfor="Ajout" class="blue-button">Ajouter une nouvelle intervention</button>
                </div>

                <dialog id="Ajout">
                    <button type="button" command="close" commandfor="Ajout" class="invisible-button"><img src="assets/Frame 1041.png" alt=""></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Ajouter une intervention</h3>
                            <p>Remplissez les informations ci-dessous</p>
                        </div>
                    </div>

                    <form action="" method="post" class="calendar-form">
                        <div>
                            <label for="title">Titre</label> </br>
                            <input type="text" placeholder="Saisissez un titre sur l'intervention" name="title" id="title" class="input-size-long"></br>
                        </div>
                        
                        <div class="form-align">
                            <div>
                                <label for="date-start" require>Date de début - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-start" id="date-start" class="select-input-size"></br>
                            </div>

                            <div>
                                <label for="date-end" require>Date de fin - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-end" id="date-end" class="select-input-size"></br>
                            </div>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="module">Module - champ obligatoire</label></br>
                                <select name="module" id="module" class= "select-size">
                                    
                                    <option value="">Sélectionner le module</option>
                                    <?php
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
                                <label for="typeintervention">Type d'intervention - champ obligatoire</label></br>
                                <select name="typeintervention" id="typeintervention" class= "select-size">
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
                        <div>
                            <label for="intervenant">Intervenant - champ obligatoire</label></br>
                            <select name="intervenant[]" id="intervenant" multiple class="select-size-long">
                                    <option value="">Sélectionner des intervenants</option>
                                    <?php
                                        $requete = $con->prepare("SELECT id, upper(last_name), first_name FROM user ORDER BY last_name");
                                        $requete->execute();
                                        $nom_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                        foreach ($nom_intervenants as $valeurs=>$element) { 
                                            echo "<option value = '{$element["id"]}' >". $element["upper(last_name)"]. " ". $element["first_name"] ."</option>";
                                        }
                                    ?>
                            </select>
                        </div>
                        <div>
                            <input type="checkbox" id="visio" name="visio" value="1" />
                            <label for="visio">Intervention effectuée en visio</label>
                        </div>
                        <div class="select-button">
                            <button commandfor="dialog" command="close" class="grey-button selection">Annuler</button>
                            <button type="submit" class="blue-button selection">Confirmer</button>
                        </div>
                    </form>
                </dialog>

            </div>
            <h4>Interventions de la semaine</h4>

            <table class="table">
                <thead>
                    <tr class="columns">
                        <td>Dates de l'intervention</td>
                        <td>Module</td>
                        <td>Type</td>
                        <td>Intervenants</td>
                        <td>En visio</td>
                        <td></td>
                    </tr>
                </thead>
                <?php
                $limit = 10;
                $offset = $page * $limit - $limit;
                $contenu = calendrier_tableau($con, $offset);
                ?>
                <tbody>
                    <?php 
                    foreach ($contenu as $valeurs=>$element) {
                        $debut = new DateTime($element["start_date"]);
                        $fin = new DateTime($element["end_date"]);
                        echo "<tr>";
                        echo "<td>". $debut->format('d/m/Y H\hi'). " à " . $fin->format('H\hi')."</td>";

                        echo "<td>". $element["module"] . "</td>";

                        echo "<td>". $element["type_name"] ."</td>";

                        $noms_intervenants = fiche_enseignant_tableau_intervenants($con, $element["id"]);
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
                        ?>
                        <td class="table_align"><img src="assets/Oeil.png" alt="">
                        <a href="">Accéder à la fiche</a></td>
                        <?php
                        echo "</tr>";

                    }
                    ?>
                </tbody>
                <?php
                $nb_pages = select_nb_pages_calendrier($con);
                $nb_pages = $nb_pages[0]["nblignes"];
                $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                ?>
            </table>
            <?php
            if ($_GET["page"] == 1 && $nb_pages == 1){ ?>
                <?php
            }
            else if ($_GET["page"] == $nb_pages){?>
                <a href="Calendrier.php?page=<?php echo $page - 1; ?>"> Page précédente </a><?php
            }
            else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?>
                <a href="Calendrier.php?page=<?php echo $page - 1; ?>">Page précédente </a>
                <a href="Calendrier.php?page=<?php echo $page + 1; ?>"> Page suivante</a>
                <?php
            } 
            else { ?>
                <a href="Calendrier.php?page=<?php echo $page + 1; ?>"> Page suivante</a><?php
            }
            ?>
        </section>
    </section>
</body>
</html>


<?php

if (!empty($_POST['date-start']) && !empty($_POST['date-end']) && !empty($_POST['module']) && !empty($_POST['typeintervention']) && !empty($_POST['intervenant'])) {
    $date_start = htmlspecialchars($_POST['date-start']);
    $date_end = htmlspecialchars($_POST['date-end']);
    $module = htmlspecialchars($_POST['module']);
    $typeintervention = htmlspecialchars($_POST['typeintervention']);
    $intervenant = $_POST['intervenant'];
    if (empty($_POST['visio'])){
        $visio = 0;
    }
    else{
        $visio = $_POST['visio'];
    }
    if (empty($_POST['title'])){
        $title = null;
    }
    else{
        $title = htmlspecialchars($_POST['title']);
    }
    $verification = verification_insert_intervention($con, $date_start, $date_end, $module, $intervenant);
    if ($verification== True){
        insert_infos_intervention($con, $title, $date_start, $date_end, $module, $typeintervention, $intervenant, $visio);
    }
    header('Location: Calendrier.php');
    exit;
}

?>

