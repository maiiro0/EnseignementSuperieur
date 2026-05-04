<?php
require_once 'inclus/auth_check.php';
require_once 'inclus/Connexion.php';
require_once '../database/User_database.php';
$active='interventions';

if (!empty($_POST['date-start']) && !empty($_POST['date-end']) && !empty($_POST['module']) && !empty($_POST['typeintervention']) && !empty($_POST['intervenant']) && empty($_POST['course_id_hidden'])) {
    $date_start = htmlspecialchars($_POST['date-start']);
    $date_end = htmlspecialchars($_POST['date-end']);
    $module_id = htmlspecialchars($_POST['module']);
    $type_id = htmlspecialchars($_POST['typeintervention']);
    $intervenant = $_POST['intervenant'];
    

    $requete = $con->prepare("SELECT name FROM module WHERE id = :id");
    $requete->bindParam(':id', $module_id);
    $requete->execute();
    $module_name = $requete->fetch(\PDO::FETCH_ASSOC)['name'];
    
    $requete = $con->prepare("SELECT name FROM intervention_type WHERE id = :id");
    $requete->bindParam(':id', $type_id);
    $requete->execute();
    $type_name = $requete->fetch(\PDO::FETCH_ASSOC)['name'];
    
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
    $verification = verification_insert_intervention($con, $date_start, $date_end, $module_name, $intervenant);
    if ($verification == True){
        insert_infos_intervention($con, $title, $date_start, $date_end, $module_name, $type_name, $intervenant, $visio);
        header('Location: Intervention.php');
        exit;
    }
}

// Traiter la suppression d'intervention
if(isset($_POST['supp-inter']) && !empty($_POST['course_id_hidden'])) {
    $course_id = htmlspecialchars($_POST['course_id_hidden']);
    
    // Supprimer les instructeurs associés
    $requete = $con->prepare("DELETE FROM course_instructor WHERE course_id = :id");
    $requete->bindParam(':id', $course_id);
    $requete->execute();
    
    // Supprimer la course
    $requete = $con->prepare("DELETE FROM course WHERE id = :id");
    $requete->bindParam(':id', $course_id);
    $requete->execute();
    
    header('Location: Intervention.php');
    exit;
}


if(isset($_POST['modif-course']) && !empty($_POST['course_id_hidden'])) {
    $course_id = htmlspecialchars($_POST['course_id_hidden']);
    $title = !empty($_POST['titre']) ? htmlspecialchars($_POST['titre']) : null;
    $date_start = htmlspecialchars($_POST['date-debut']);
    $date_end = htmlspecialchars($_POST['date-fin']);
    $module_id = htmlspecialchars($_POST['modif-module']);
    $type_id = htmlspecialchars($_POST['modif-intervention']);
    $intervenant = $_POST['intervenant'] ?? [];
    $visio = !empty($_POST['modif-visio']) ? 1 : 0;
    
    if (!empty($date_start) && !empty($date_end) && !empty($module_id) && !empty($type_id) && !empty($intervenant)) {
        $requete = $con->prepare("UPDATE course SET title = :title, start_date = :start_date, end_date = :end_date, module_id = :module_id, intervention_type_id = :type_id, remotely = :remotely WHERE id = :id");
        $requete->bindParam(':title', $title);
        $requete->bindParam(':start_date', $date_start);
        $requete->bindParam(':end_date', $date_end);
        $requete->bindParam(':module_id', $module_id);
        $requete->bindParam(':type_id', $type_id);
        $requete->bindParam(':remotely', $visio);
        $requete->bindParam(':id', $course_id);
        $requete->execute();
        
        $requete = $con->prepare("DELETE FROM course_instructor WHERE course_id = :id");
        $requete->bindParam(':id', $course_id);
        $requete->execute();
        

        foreach ($intervenant as $user_id) {
            $requete = $con->prepare("SELECT i.id FROM instructor i WHERE user_id = :user_id");
            $requete->bindParam(':user_id', $user_id);
            $requete->execute();
            $instructor = $requete->fetch(\PDO::FETCH_ASSOC);
            
            if ($instructor) {
                $requete = $con->prepare("INSERT INTO course_instructor (course_id, instructor_id) VALUES (:course_id, :instructor_id)");
                $requete->bindParam(':course_id', $course_id);
                $requete->bindParam(':instructor_id', $instructor['id']);
                $requete->execute();
            }
        }
        
        header('Location: Intervention.php');
        exit;
    }
}


require_once 'inclus/Header.php';


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
$courseId = $_GET['course_id'] ?? '';

$courseData = null;
$courseInstructorIds = [];
if (!empty($courseId)) {
    $requete = $con->prepare("SELECT c.id, c.title, c.start_date, c.end_date, c.module_id, c.intervention_type_id, c.remotely, m.name as module_name, it.name as type_name 
                             FROM course c 
                             JOIN module m ON c.module_id = m.id 
                             JOIN intervention_type it ON c.intervention_type_id = it.id 
                             WHERE c.id = :id");
    $requete->bindParam(':id', $courseId);
    $requete->execute();
    $courseData = $requete->fetch(\PDO::FETCH_ASSOC);
    
    if ($courseData) {
        // Récupérer les IDs des intervenants
        $requete = $con->prepare("SELECT u.id FROM user u 
                                 JOIN instructor i ON i.user_id = u.id 
                                 JOIN course_instructor ci ON ci.instructor_id = i.id 
                                 WHERE ci.course_id = :id");
        $requete->bindParam(':id', $courseId);
        $requete->execute();
        $instructors = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $courseInstructorIds = array_column($instructors, 'id');
    }
}

// Récupérer la liste des modules pour les selects
$requete = $con->prepare("SELECT id, name FROM module ORDER BY id");
$requete->execute();
$modules = $requete->fetchAll(\PDO::FETCH_ASSOC);

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
                                        echo "<option value='{$element['id']}'>". $element["name"] ."</option>";
                                    }
                                    ?>
                                </select></br>
                            </div>
                            <div>
                                <label for="typeintervention">Type d'intervention - champ obligatoire</label></br>
                                <select name="typeintervention" id="typeintervention" class= "select-size">
                                    <option value="">Sélectionner le type</option>
                                    <?php 
                                    $requete = $con->prepare("SELECT id, name FROM intervention_type ORDER BY name");
                                    $requete->execute();
                                    $nom_intervention = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                    foreach ($nom_intervention as $valeurs=>$element) { 
                                        echo "<option value='{$element['id']}'>". $element["name"]."</option>";
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

                <dialog  id="Modif" <?= !empty($courseId) && $courseData ? 'open' : '' ?>>
                    <button type="button" command="close" commandfor="Modif" class="invisible-button"><img src="assets/Frame 1041.png" alt=""></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Modifier une intervention</h3>
                            <p>Remplissez les informations ci-dessous</p>
                        </div>
                    </div>

                    <form action="" method="post" class="calendar-form">
                        <input type="hidden" name="course_id_hidden" value="<?= $courseData ? htmlspecialchars($courseData['id']) : '' ?>">
                        
                        <div>
                            <label for="titre">Titre</label> </br>
                            <input type="text" placeholder="Saisissez un titre sur l'intervention" name="titre" id="titre" class="input-size-long" value="<?= $courseData ? htmlspecialchars($courseData['title']) : '' ?>"></br>
                        </div>
                        
                        <div class="form-align">
                            <div>
                                <label for="date-debut" require>Date de début - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-debut" id="date-debut" class="select-input-size" value="<?= $courseData ? htmlspecialchars(str_replace(' ', 'T', $courseData['start_date'])) : '' ?>"></br>
                            </div>

                            <div>
                                <label for="date-fin" require>Date de fin - champ obligatoire</label></br>
                                <input type="datetime-local" name="date-fin" id="date-fin" class="select-input-size" value="<?= $courseData ? htmlspecialchars(str_replace(' ', 'T', $courseData['end_date'])) : '' ?>"></br>
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
                                        $selected = ($courseData && $courseData['module_id'] == $element['id']) ? 'selected' : '';
                                        echo "<option value='{$element['id']}' {$selected}>". $element["name"] ."</option>";
                                    }
                                        
                                    ?>
                                </select></br>
                            </div>

                            <div>
                                <label for="modif-intervention">Type d'intervention - champ obligatoire</label></br>
                                <select name="modif-intervention" id="modif-intervention" class= "select-size">
                                    <option value="">Sélectionner le type</option>
                                    <?php 
                                    
                                    $requete = $con->prepare("SELECT id, name FROM intervention_type ORDER BY name");
                                    $requete->execute();
                                    $nom_intervention = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                    foreach ($nom_intervention as $valeurs=>$element) { 
                                        $selected = ($courseData && $courseData['intervention_type_id'] == $element['id']) ? 'selected' : '';
                                        echo "<option value='{$element['id']}' {$selected}>". $element["name"]."</option>";
                                    }
                                        
                                    ?>
                                </select></br>
                            </div>
                        </div>
                        <div>
                            <label for="modif-inter">Intervenant - champ obligatoire</label></br>
                            <select name="intervenant[]" id="modif-inter" multiple class="select-size-long">
                                    <option value="">Sélectionner des intervenants</option>
                                    <?php
                                        $requete = $con->prepare("SELECT id, upper(last_name) as last_name, first_name FROM user ORDER BY last_name");
                                        $requete->execute();
                                        $nom_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
                                        foreach ($nom_intervenants as $valeurs=>$element) { 
                                            $selected = (in_array($element["id"], $courseInstructorIds)) ? 'selected' : '';
                                            echo "<option value='{$element["id"]}' {$selected}>". $element["last_name"]. " ". $element["first_name"] ."</option>";
                                        }
                                    
                                    ?>
                                    
                            </select>
                        </div>
                        <div>
                            <input type="checkbox" id="modif-visio" name="modif-visio" value="1" <?= ($courseData && $courseData['remotely'] == 1) ? 'checked' : '' ?>>
                            <label for="modif-visio">Intervention effectuée en visio</label>
                        </div>
                        <div class="select-button-gap">
                            <a href="Intervention.php" class="grey-button selection">Annuler</a>
                            <button type="submit" name="supp-inter" class="red-button selection">Supprimer</button>
                            <button type="submit" name="modif-course" class="blue-button selection">Confirmer</button>
                        </div>
                    </form>
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
                        echo substr($temporaire, 2); //substr recupère à partir d'un certain endroit la chaîne de caractère.
                        echo "</td>";


                        if ($element["remotely"] == 0){
                            ?><td> <img src="assets/VisioOff.png" alt=""> </td><?php
                        }   
                        else {
                            ?><td> <img src="assets/VisioOn.png" alt=""> </td><?php
                        }
                        ?><td class="table_align"><img src="assets/Oeil.png" alt="">
                        <a href="?course_id=<?php echo $element['id']; ?>" class="modification-button">Accéder à la fiche</a></td>
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
<?php?>

<?php


if (!empty($_POST['date-start']) && !empty($_POST['date-end']) && !empty($_POST['module']) && !empty($_POST['typeintervention']) && !empty($_POST['intervenant']) && empty($_POST['course_id_hidden'])) {
    $date_start = htmlspecialchars($_POST['date-start']);
    $date_end = htmlspecialchars($_POST['date-end']);
    $module_id = htmlspecialchars($_POST['module']);
    $type_id = htmlspecialchars($_POST['typeintervention']);
    $intervenant = $_POST['intervenant'];
    

    $requete = $con->prepare("SELECT name FROM module WHERE id = :id");
    $requete->bindParam(':id', $module_id);
    $requete->execute();
    $module_name = $requete->fetch(\PDO::FETCH_ASSOC)['name'];
    
    $requete = $con->prepare("SELECT name FROM intervention_type WHERE id = :id");
    $requete->bindParam(':id', $type_id);
    $requete->execute();
    $type_name = $requete->fetch(\PDO::FETCH_ASSOC)['name'];
    
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
    $verification = verification_insert_intervention($con, $date_start, $date_end, $module_name, $intervenant);
    if ($verification == True){
        insert_infos_intervention($con, $title, $date_start, $date_end, $module_name, $type_name, $intervenant, $visio);
        header('Location: Intervention.php');
        exit;
    }
}

    if(isset($_POST['supp-inter']) && !empty($_POST['course_id_hidden'])) {
    $course_id = htmlspecialchars($_POST['course_id_hidden']);
    
    $requete = $con->prepare("DELETE FROM course_instructor WHERE course_id = :id");
    $requete->bindParam(':id', $course_id);
    $requete->execute();
    
    $requete = $con->prepare("DELETE FROM course WHERE id = :id");
    $requete->bindParam(':id', $course_id);
    $requete->execute();
    
    header('Location: Intervention.php');
    exit;
}

if(isset($_POST['modif-course']) && !empty($_POST['course_id_hidden'])) {
    $course_id = htmlspecialchars($_POST['course_id_hidden']);
    $title = !empty($_POST['titre']) ? htmlspecialchars($_POST['titre']) : null;
    $date_start = htmlspecialchars($_POST['date-debut']);
    $date_end = htmlspecialchars($_POST['date-fin']);
    $module_id = htmlspecialchars($_POST['modif-module']);
    $type_id = htmlspecialchars($_POST['modif-intervention']);
    $intervenant = $_POST['intervenant'] ?? [];
    $visio = !empty($_POST['modif-visio']) ? 1 : 0;
    
    if (!empty($date_start) && !empty($date_end) && !empty($module_id) && !empty($type_id) && !empty($intervenant)) {
        $requete = $con->prepare("UPDATE course SET title = :title, start_date = :start_date, end_date = :end_date, module_id = :module_id, intervention_type_id = :type_id, remotely = :remotely WHERE id = :id");
        $requete->bindParam(':title', $title);
        $requete->bindParam(':start_date', $date_start);
        $requete->bindParam(':end_date', $date_end);
        $requete->bindParam(':module_id', $module_id);
        $requete->bindParam(':type_id', $type_id);
        $requete->bindParam(':remotely', $visio);
        $requete->bindParam(':id', $course_id);
        $requete->execute();
        
        $requete = $con->prepare("DELETE FROM course_instructor WHERE course_id = :id");
        $requete->bindParam(':id', $course_id);
        $requete->execute();
        
        foreach ($intervenant as $user_id) {
            $requete = $con->prepare("SELECT i.id FROM instructor i WHERE user_id = :user_id");
            $requete->bindParam(':user_id', $user_id);
            $requete->execute();
            $instructor = $requete->fetch(\PDO::FETCH_ASSOC);
            
            if ($instructor) {
                $requete = $con->prepare("INSERT INTO course_instructor (course_id, instructor_id) VALUES (:course_id, :instructor_id)");
                $requete->bindParam(':course_id', $course_id);
                $requete->bindParam(':instructor_id', $instructor['id']);
                $requete->execute();
            }
        }
        
        header('Location: Intervention.php');
        exit;
    }
}

?>



