
<?php 
require_once 'inclus/Connexion.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
$active='enseignants';

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $infos = select_infos_enseignant($con, $id);
}

?>

<body>
    <nav>
        <?php include_once 'inclus/Menu_gestion_licence.php' ?>
    </nav>
    <section class="teacher-information page">
        <div class="breadcrumb">  
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Corps_enseignant.php">Corps enseignant</a>
            <p>></p>
            <a href="Fiche_enseignant_informations.php?id=<?php echo $id; ?>"> <span><?php echo $infos["first_name"];?></span> <span><?php echo $infos["last_name"];?></span> </a>
            <p>></p>
            <a href="#">Interventions</a>
        </div>

        <section>
            <div class="align margin-null">
                <h3 class="margin-null"><span><?php echo $infos["first_name"];?></span>
                <span><?php echo $infos["last_name"];?></span></h3>
            </div>
            <p class="yellow-title">Modules enseignés</p>
            <div class="information-part">
            <?php

                $contenu = select_infos_modules_enseignant($con, $id);

                foreach ($contenu as $colonne => $element) {
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
            <h4>Interventions trouvées : </h4>

            <table class="table_teacher_interventions">
                <tr class="columns">
                    <td>Dates de l'intervention</td>
                    <td>Module</td>
                    <td>Type</td>
                    <td>Intervenants</td>
                    <td>En visio</td>
                </tr>
                <?php
                if (!empty($_GET["start_date"]) || !empty($_GET["end_date"]) || !empty($_GET["name"])){
                    $filtre_start_date = '%'.$_GET["start_date"].'%';
                    $filtre_end_date = '%'.$_GET["end_date"].'%';
                    $filtre_name = '%'.$_GET["name"].'%';


                    if (empty($_GET["start_date"])){
                        $filtre_start_date = '';
                    }

                    if (empty($_GET["end_date"])){
                        $filtre_end_date = '';
                    }

                    if (empty($_GET["name"])){
                        $filtre_name = '';
                    }

                    $contenu = filtre_fiche_enseignant($con, $id,  $filtre_start_date, $filtre_end_date, $filtre_name);

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
                    }
                }

                else {
                    $contenu = fiche_enseignant_tableau($con, $id );

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

                    }
                }
                ?>
            </table>
        </section>
    </section>
</body>
</html>
