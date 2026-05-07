
<?php 
require_once 'inclus/auth_check.php';
require_once 'inclus/Connexion.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
$active='enseignants';
if (empty($_GET["page"])){ // Vérification de la pagination dans l'URL. Si pas de numéro de page, on considère que c'est la page 1 par défaut
    $page = 1;
    $_GET["page"] = 1;
}
else { // Récupère le numéro de la page dans l'URL
    $page = $_GET['page'];
}

if (isset($_GET['id'])) { // Vérification que l'id de l'enseignant est dans l'URL
    $id = htmlspecialchars($_GET['id']);
    $infos = select_infos_enseignant($con, $id); // Récupération des informations de l'enseignant à partir de son id
}


if (empty($_GET['start_date'])) { // Vérification que le filtre de date de début est présent dans l'URL
    $filtre_start_date = '';
    $_GET['start_date'] ="";
}
else { // Récupération du filtre de date de début dans l'URL
    $filtre_prenom = $_GET['start_date'];
}

if (empty($_GET["end_date"])){ // Vérification que le filtre de date de fin est présent dans l'URL
    $filtre_end_date = '';
    $_GET["end_date"] ="";
}
else { // Récupération du filtre de date de fin dans l'URL
    $filtre_nom = $_GET["end_date"];
}

if (empty($_GET["name"])){ // Vérification que le filtre de nom de module est présent dans l'URL
    $filtre_name = '';
    $_GET["name"] ="";
}
else { // Récupération du filtre de nom de module dans l'URL
    $filtre_email = $_GET["name"];
}


?>

<body>
    <nav>
        <?php include_once 'inclus/Menu_gestion_licence.php' ?>
    </nav>
    <section class="teacher-information page">
        <div class="breadcrumb"> <!-- Fil d'ariane -->
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Corps_enseignant.php">Corps enseignant</a>
            <p>></p>
            <a href="Fiche_enseignant_informations.php?id=<?php echo $id; ?>"> <span><?php echo $infos["first_name"];?></span> <span><?php echo $infos["last_name"];?></span> </a> 
            <!-- Le nom et le prénom de l'enseignant sont affichés dans le fil d'ariane pour indiquer que c'est la page de cet enseignant -->
            <p>></p>
            <a href="#">Interventions</a>
        </div>

        <section>
            <div class="align margin-null">
                <h3 class="margin-null"><span><?php echo $infos["first_name"];?></span> <!-- Affichage du prénom de l'enseignant dans le titre de la page -->
                <span><?php echo $infos["last_name"];?></span></h3>
            </div>
            <p class="yellow-title">Modules enseignés</p>
            <div class="information-part">
            <?php

                $contenu = select_infos_modules_enseignant($con, $id); // Récupération des modules enseignés par l'enseignant à partir de son id

                foreach ($contenu as $colonne => $element) { // On prend 1 module enseigné par 1 module enseigné pour les afficher
                    echo"<p>";
                    echo "<span>". $element["name"]. "</span>"; ?>
                    <span class="padding-5">:</span>
                    <?php
                    echo"<span>". $element['hours_count']. "</span>";
                    echo"<span>"."h00". "</span>" ;
                    echo"</p>";
                }
            ?>  
            </div>
        </section>
        <section class="form-part">

            <div class="link-part">
                <a href="Fiche_enseignant_informations.php?id=<?php echo $id; ?>" class="link-unselected">Informations générales</a>
                <a href="#"  class="link-select">Interventions</a>
            </div>
            <p class="yellow-title">Filtrer les interventions</p>
            <form method="get" action="" class="teacher-information-form">
                <div class="filter-row">
                    <div class="filter-column">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <label name="start_date">Date de debut</label>
                        <input type="text" name="start_date" placeholder="Saisissez la date de debut">
                    </div>
                    <div class="filter-column">
                        <label name="end_date">Date de fin</label>
                        <input type="text" name="end_date" placeholder="Saisissez la date de fin">
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

            <table class="table_teacher_interventions">
                <thead>
                    <tr class="columns">
                        <td>Dates de l'intervention</td>
                        <td>Module</td>
                        <td>Type</td>
                        <td>Intervenants</td>
                        <td>En visio</td>
                    </tr>
                </thead>
                <?php
                if (!empty($_GET["start_date"]) || !empty($_GET["end_date"]) || !empty($_GET["name"])){
                    $filtre_start_date = '%'.$_GET["start_date"].'%';
                    $filtre_end_date = '%'.$_GET["end_date"].'%';
                    $filtre_name = '%'.$_GET["name"].'%';

                    $limit = 10;
                    $offset = $page * $limit - $limit;


                    if (empty($_GET["start_date"])){
                        $filtre_start_date = '';
                    }

                    if (empty($_GET["end_date"])){
                        $filtre_end_date = '';
                    }

                    if (empty($_GET["name"])){
                        $filtre_name = '';
                    }

                    $contenu = filtre_fiche_enseignant($con, $id,  $filtre_start_date, $filtre_end_date, $filtre_name, $offset);
                    echo "<h4>".count($contenu)." interventions trouvées</h4>";
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


                            if ($element["remotely"] == 0){ // Vérification que l'intervention est en visio ou non, si l'intervention n'est pas en visio, on affiche une icône pour indiquer que ce n'est pas une intervention en visio
                                ?><td> <img src="assets/VisioOff.png" alt=""> </td><?php
                            }   
                            else {
                                ?><td> <img src="assets/VisioOn.png" alt=""> </td><?php
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    <?php
                        

                    $nb_pages = select_nb_pages_filtre_fiche_enseignant($con,$id, $filtre_start_date, $filtre_end_date, $filtre_name); // Récupération du nombre de pages pour la pagination en fonction du nombre d'interventions trouvées avec les filtres, les informations récupérées sont le nombre d'interventions trouvées
                    $nb_pages = $nb_pages[0]["nblignes"];
                    $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1; //Pagination
                    
                }

                else {
                    $limit = 10;
                    $offset = $page * $limit - $limit; //Pagination en fonction du numéro de la page dans l'URL
                    $contenu = fiche_enseignant_tableau($con, $id ,$offset);
                    echo "<h4>".count($contenu)." interventions trouvées</h4>";
                    ?>
                    <tbody>
                        <?php
                        foreach ($contenu as $valeurs=>$element) {
                            $debut = new DateTime($element["start_date"]); // Création d'un objet DateTime à partir de la date de début de l'intervention pour pouvoir l'afficher dans un format plus lisible
                            $fin = new DateTime($element["end_date"]); // Création d'un objet DateTime à partir de la date de fin de l'intervention pour pouvoir l'afficher dans un format plus lisible
                            echo "<tr>";
                            echo "<td>". $debut->format('d/m/Y H\hi'). " à " . $fin->format('H\hi')."</td>"; // Renvoie de la date de début et de fin de l'intervention dans le format jour/mois/année heure:minute pour la date de début et heure:minute pour la date de fin

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


                            if ($element["remotely"] == 0){ // Vérification que l'intervention est en visio ou non, si l'intervention n'est pas en visio, on affiche une icône pour indiquer que ce n'est pas une intervention en visio
                                ?><td> <img src="assets/VisioOff.png" alt=""> </td><?php
                            }   
                            else {
                                ?><td> <img src="assets/VisioOn.png" alt=""> </td><?php
                            }
                            echo "</tr>";

                        }
                        ?>
                    </tbody>
                    <?php
                    $nb_pages = select_nb_pages_filtre_fiche_enseignant($con,$id, $filtre_start_date, $filtre_end_date, $filtre_name);
                    $nb_pages = $nb_pages[0]["nblignes"];
                    $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1; //Pagination
                }
                ?>
            </table>
            <?php
            if ($_GET["page"] == 1 && $nb_pages == 1){ ?> <!-- Si il n'y a qu'une seule page, on n'affiche pas les liens de pagination -->
                <?php
            }
            else if ($_GET["page"] == $nb_pages){?> <!-- Si on est sur la dernière page, on n'affiche que le lien de la page précédente -->
                <a href="Fiche_enseignant_interventions.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $page - 1; ?>&start_date=<?php echo $filtre_start_date; ?>&end_date=<?php echo $filtre_end_date; ?>&name=<?php echo $filtre_name; ?>">Page précédente </a><?php
            }
            else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?> <!-- Si on est sur une page intermédiaire, on affiche les liens de la page précédente et de la page suivante -->
                <a href="Fiche_enseignant_interventions.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $page - 1; ?>&start_date=<?php echo $filtre_start_date; ?>&end_date=<?php echo $filtre_end_date; ?>&name=<?php echo $filtre_name; ?>">Page précédente </a>
                <a href="Fiche_enseignant_interventions.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $page + 1; ?>&start_date=<?php echo $filtre_start_date; ?>&end_date=<?php echo $filtre_end_date; ?>&name=<?php echo $filtre_name; ?>"> Page suivante</a>
                <?php
            } 
            else { ?> <!-- Si on est sur la première page, on n'affiche que le lien de la page suivante -->
                <a href="Fiche_enseignant_interventions.php?id=<?php echo $_GET['id']; ?>&page=<?php echo $page + 1; ?>&start_date=<?php echo $filtre_start_date; ?>&end_date=<?php echo $filtre_end_date; ?>&name=<?php echo $filtre_name; ?>"> Page suivante</a><?php
            }
            ?>    
        </section>
    </section>
</body>
</html>

