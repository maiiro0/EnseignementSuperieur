<?php
require_once 'inclus/auth_check.php';
require_once 'inclus/Connexion.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
$active='interventions';


if (empty($_GET["page"])){
    $page = 1;
    $_GET["page"] = 1;
}
else {
    $page = $_GET['page'];
}


$dateStart = $_GET['date_start'] ?? '';
$dateEnd = $_GET['date_end'] ?? '';
$moduleId = $_GET['module_id'] ?? '';


// fonction ajt intervention
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
    header("Location: Intervention.php");
    exit();
}




?>


<body>

    <nav>
        <?php require_once 'inclus/Menu_gestion_licence.php'?>
    </nav>

    <section class="calendar page">
        <div class="breadcrumb">
            <a href="#"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="#">Interventions</a>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Interventions</h3>
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

                <dialog  id="Modif">
                    <button type="button" command="close" commandfor="Modif" class="invisible-button"><img src="assets/Frame 1041.png" alt=""></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Modifier une intervention</h3>
                            <p>Remplissez les informations ci-dessous</p>
                        </div>
                    </div>

                    <form action="" method="post" class="calendar-form">
                        <div>
                            <label for="titre">Titre</label> </br>
                            <input type="text" placeholder="Saisissez un titre sur l'intervention" name="titre" id="titre" class="input-size-long"></br>
                        </div>
                        
                        <div class="form-align">
                            <div>
                                <label for="date-debut" require>Date de début - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-debut" id="date-debut" class="select-input-size"></br>
                            </div>

                            <div>
                                <label for="date-fin" require>Date de fin - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-fin" id="date-fin" class="select-input-size"></br>
                            </div>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="modif-module">Module - champ obligatoire</label></br>
                                <select name="modif-module" id="modif-module" class= "select-size">
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
                                <label for="modif-intervention">Type d'intervention - champ obligatoire</label></br>
                                <select name="modif-intervention" id="modif-intervention" class= "select-size">
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
                            <label for="modif-inter">Intervenant - champ obligatoire</label></br>
                            <select name="intervenant[]" id="intervenant" multiple class="select-size-long">
                                    <option value="">Sélectionner des intervenants</option>
                                    <?php
                                    
                                        $requete = $con->prepare("SELECT upper(last_name), first_name FROM user ORDER BY last_name");
                                        $requete->execute();
                                        $nom_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                        foreach ($nom_intervenants as $valeurs=>$element) { 
                                            echo "<option>". $element["upper(last_name)"]. " ". $element["first_name"] ."</option>";
                                        }
                                    
                                    ?>
                                    
                            </select>
                        <div>
                        <div>
                            <input type="checkbox" id="modif-visio" name="modif-visio" value="1" >
                            <label for="modif-visio">Intervention effectuée en visio</label>
                        </div>
                        <div class="select-button-gap">
                            <a href="#" class="grey-button selection">Annuler</a>
                            <button type="submit" name="supp-inter" class="red-button selection">Supprirmer</button>
                            <button type="submit" class="blue-button selection">Confirmer</button>
                        </div>
                        <?php
                        
                            if(isset($_POST['supp-inter'])) {
                                $requete = $con->prepare("DELETE FROM course WHERE id = :id");
                                $requete->bindParam(':id', $id);
                                $requete->execute();
                            }
                            if(isset($_POST['titre']) && isset($_POST['date-debut']) && isset($_POST['date-fin']) && isset($_POST['modif-module']) && isset($_POST['modif-intervention']) && isset($_POST['modif-inter'])) {
                                $requete = $con->prepare("INSERT INTO course (title, start_date, end_date, module_id, intervention_type_id) VALUES (:title, :start_date, :end_date, :module_id, :intervention_type_id)");
                                $requete->bindParam(':title', $_POST['titre']);
                                $requete->bindParam(':start_date', $_POST['date-debut']);
                                $requete->bindParam(':end_date', $_POST['date-fin']);
                                $requete->bindParam(':module_id', $_POST['modif-module']);
                                $requete->bindParam(':intervention_type_id', $_POST['modif-intervention']);
                                $requete->execute();
                            }
                        
                        ?>
                    </form>
                    </div>
                </dialog>
                        
            </div>

            <form method="GET" action="">
                <h3 class="yellow">Filtres</h3>
                <div class="filter-row">
                    <div class="filter-column">
                        <label for="date_start">Date de début</label>
                        <input
                            type="datetime-local"
                            name="date_start"
                            id="date_start"
                            value="<?= htmlspecialchars($dateStart) ?>"
                        >
                    </div>

                    <div class="filter-column">
                        <label for="date_end">Date de fin</label>
                        <input
                            type="datetime-local"
                            name="date_end"
                            id="date_end"
                            value="<?= htmlspecialchars($dateEnd) ?>"
                        >
                    </div>

                    <div class="filter-column">
                        <label for="module_id">Module</label>
                        <select name="module_id" id="module_id">
                            <option value="">Sélectionnez le module</option>
                            <?php foreach ($modules as $module): ?>
                                <option
                                    value="<?= $module['id'] ?>"
                                    <?= ($moduleId == $module['id']) ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($module['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="yellow-button">Filtrer</button>
                </div>
            </form>

            <?php
                $interventions = calendrier_tableau_Count($con);
            ?>

            <p class="result-count"><?= count($interventions) ?> interventions trouvées</p>

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
                        ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                        <button type="button" command="show-modal" commandfor="Modif" value = '{<?php echo $element['id'] ?> }' class= "modif-button">Accéder à la fiche</button></td>
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
                <a href="Intervention.php?page=<?php echo $page - 1; ?>"> Page précédente </a><?php
            }
            else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?>
                <a href="Intervention.php?page=<?php echo $page - 1; ?>">Page précédente </a>
                <a href="Intervention.php?page=<?php echo $page + 1; ?>"> Page suivante</a>
                <?php
            } 
            else { ?>
                <a href="Intervention.php?page=<?php echo $page + 1; ?>"> Page suivante</a><?php
            }
            ?>
        </section>
    </section>
</body>
</html>






