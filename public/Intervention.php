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
    if ($verification=== True){
        insert_infos_intervention($con, $title, $date_start, $date_end, $module, $typeintervention, $intervenant, $visio);
        header("Location: Intervention.php");
        exit();
    }
}


$infos_inter = null;
$intervenants_ids = [];

// Si on a cliqué sur "Accéder à la fiche"
if (isset($_POST['id_inter']) && isset($_POST['charger_fiche'])) {
    $id_select = (int)$_POST['id_inter'];

    // On récupère les infos de l'intervention
    $stmt = $con->prepare("SELECT * FROM course WHERE id = :id");
    $stmt->execute([':id' => $id_select]);
    $infos_inter = $stmt->fetch(\PDO::FETCH_ASSOC);

    // On récupère les ID des intervenants liés
    $stmt2 = $con->prepare("SELECT instructor_id FROM course_instructor WHERE course_id = :id");
    $stmt2->execute([':id' => $id_select]);
    $intervenants_ids = $stmt2->fetchAll(PDO::FETCH_COLUMN);

    // On déclenche l'ouverture de la modal au chargement de la page
    echo "<script>document.addEventListener('DOMContentLoaded', () => { document.getElementById('Modif').showModal(); });</script>";
}


// SUPPRIMER
if (isset($_POST['action_supprimer']) && !empty($_POST['id_action'])) {
    $id = (int)$_POST['id_action'];
    
    try {
        // On supprime d'abord les liens entre l'intervention et les intervenants
        $stmt_del_links = $con->prepare("DELETE FROM course_instructor WHERE course_id = ?");
        $stmt_del_links->execute([$id]);

        // On supprime l'intervention elle-même
        $stmt_del_course = $con->prepare("DELETE FROM course WHERE id = ?");
        $stmt_del_course->execute([$id]);

        // Redirection pour rafraîchir la page
        header("Location: Intervention.php");
        exit();
    } catch (Exception $e) {
        echo "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// --- ACTION MODIFIER ---
if (isset($_POST['action_modifier']) && !empty($_POST['id_action'])) {
    $id = (int)$_POST['id_action'];
    
    // Récupération des données du formulaire
    $titre = $_POST['titre'];
    $date_debut = $_POST['date-debut'];
    $date_fin = $_POST['date-fin'];
    $module_id = $_POST['modif-module'];
    $type_id = $_POST['modif-intervention'];
    $visio = isset($_POST['modif-visio']) ? 1 : 0;
    $intervenants = $_POST['intervenants'] ?? []; // Tableau des IDs d'instructeurs
    $verif = verification_modification_intervention($con, $date_debut, $date_fin, $module_id, $intervenants);
    if ($verif=== True){
        try {
            // Mise à jour de la table 'course'
            $sql_update = "UPDATE course SET 
                            title = ?, 
                            start_date = ?, 
                            end_date = ?, 
                            module_id = ?, 
                            intervention_type_id = ?, 
                            remotely = ? 
                        WHERE id = ?";
            $stmt_up = $con->prepare($sql_update);
            $stmt_up->execute([$titre, $date_debut, $date_fin, $module_id, $type_id, $visio, $id]);

            // Mise à jour des intervenants  (on supprime tout et on rémet à jour)
            $stmt_clean = $con->prepare("DELETE FROM course_instructor WHERE course_id = ?");
            $stmt_clean->execute([$id]);

            if (!empty($intervenants)) {
                $stmt_ins = $con->prepare("INSERT INTO course_instructor (course_id, instructor_id) VALUES (?, ?)");
                foreach ($intervenants as $instructor_id) {
                    $stmt_ins->execute([$id, $instructor_id]);
                }
            }

            // Redirection
            header("Location: Intervention.php");
            exit();
        } catch (Exception $e) {
            echo "Erreur lors de la modification : " . $e->getMessage();
        }
    }
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
                            <input type="text" value="<?= htmlspecialchars($infos_inter['title'] ?? '') ?>" name="title" id="title" class="input-size-long"></br>
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
                                    <option value="">Sélectionner le type d'intervention</option>
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
                            <label for="intervenant">Intervenant - champ obligatoire  - Ctrl pour sélectionner plusieurs intervenants</label></br>
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
                            <button type="button" command="close" commandfor="Ajout" class="grey-button selection">Annuler</button>
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
                            <input type="text" name="titre" id="titre" class="input-size-long" 
                            value="<?php echo htmlspecialchars($infos_inter['title'] ?? ''); ?>"></br>
                        </div>
                        
                        <div class="form-align">
                            <div>
                                <label for="date-debut" require>Date de début - champ obligatoire</label></br>
                               <input type="datetime-local" name="date-debut" class="select-input-size" 
                                value="<?= isset($infos_inter['start_date']) ? date('Y-m-d\TH:i', strtotime($infos_inter['start_date'])) : '' ?>"></br>
                            </div>

                            <div>
                                <label for="date-fin" require>Date de fin - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-fin" class="select-input-size"
                                value="<?= isset($infos_inter['end_date']) ? date('Y-m-d\TH:i', strtotime($infos_inter['end_date'])) : '' ?>"></br>
                            </div>
                        </div>

                        <div class="form-align">
                            <div>
                                <label for="modif-module">Module - champ obligatoire</label></br>
                                <select name="modif-module" class="select-size">
                                    <?php
                                    $req = $con->query("SELECT id, name FROM module ORDER BY name");
                                    while($m = $req->fetch()) {
                                        $selected = ($infos_inter['module_id'] == $m['id']) ? 'selected' : '';
                                        echo "<option value='{$m['id']}' $selected>{$m['name']}</option>";
                                    }
                                    ?>
                                </select></br> 
                            
                            </div>

                            <div>
                                <label for="modif-intervention">Type d'intervention - champ obligatoire</label></br>
                                <select name="modif-intervention" class="select-size">
                                    <?php 
                                    $req = $con->query("SELECT id, name FROM intervention_type ORDER BY name");
                                    while($t = $req->fetch()) {
                                        $selected = ($infos_inter['intervention_type_id'] == $t['id']) ? 'selected' : '';
                                        echo "<option value='{$t['id']}' $selected>{$t['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="modif-inter">Intervenant - champ obligatoire - Ctrl pour sélectionner plusieurs intervenants</label></br>
                            <select name="intervenants[]" multiple class="select-size-long">
                                <?php
                                // On récupère les utilisateurs qui sont des instructeurs
                                $req = $con->query("SELECT i.id, u.last_name, u.first_name FROM instructor i JOIN user u ON i.user_id = u.id ORDER BY u.last_name");
                                while($u = $req->fetch()) {
                                    // $intervenants_ids a été rempli lors de l'étape 2 (récupération)
                                    $selected = in_array($u['id'], $intervenants_ids) ? 'selected' : '';
                                    echo "<option value='{$u['id']}' $selected>".strtoupper($u['last_name'])." ".$u['first_name']."</option>";
                                }
                                ?>
                            </select></br>
                        </div>
                        <div>
                            <input type="checkbox" id="modif-visio" name="modif-visio" value="1" <?= ($infos_inter['remotely'] ?? 0) == 1 ? 'checked' : '' ?> />
                            <label for="modif-visio">Intervention effectuée en visio</label>
                        </div>
                        <div class="select-button-gap">
                            <button type="button" command="close" commandfor="Modif" class="grey-button selection">Annuler</button>

                            <button type="submit" name="action_supprimer" class="red-button selection" onclick="return confirm('Supprimer définitivement ?')">Supprimer</button>

                            <button type="submit" name="action_modifier" class="blue-button selection">Confirmer</button>

                            <input type="hidden" name="id_action" value="<?php echo $infos_inter['id']; ?>">
                        </div>
                    </form>
                </dialog>
                        
            </div>

            <form method="get" action="" class="teacher-information-form">
                <div class="filter-row">
                    <div class="filter-column">
                        <input type="hidden" name="id">
                        <label name="start_date">Date de debut</label>
                        <input type="datetime-local" name="start_date">
                    </div>
                    <div class="filter-column">
                        <label name="end_date">Date de fin</label>
                        <input type="datetime-local" name="end_date">
                    </div>
                    <div class="filter-column">
                        <label for="name">Module</label>
                        <select name="name" id="name" >
                                <option value="">Sélectionnez le module</option>
                                <?php
                                    $nom_module = select_modules_corp_enseignant($con);
                                    foreach ($nom_module as $valeurs=>$element) { 
                                        echo "<option>". $element["name"]."</option>";
                                    }
                                ?>
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
                        <td>Date de l'intervention</td>
                        <td>Module & Titre</td>
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

                        echo "<td>". $element["module"] ;
                        if (!empty ($element["title"])){
                            echo "<br>";
                            echo $element["title"] ;
                        }
                        echo "</td>" ;

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
                        <td class="table_align">
                            <img src="assets/Oeil.png" alt="">
                            <form method="post" action="" class="form_margin">
                                <input type="hidden" name="id_inter" value="<?php echo $element['id']; ?>">
                                <button type="submit" name="charger_fiche" class="modif-button">Accéder à la fiche</button>
                            </form>
                        </td>
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






