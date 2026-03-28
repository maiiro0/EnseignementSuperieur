
<?php 
require_once 'Connexion.php';

/*
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=1");
    // $requete->bindParam(':id', $id);
    $requete->execute();
    $infos = $requete ->fetchAll(\PDO::FETCH_ASSOC);
}
*/
$id =11;
$requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=:id");
$requete->bindParam(':id', $id);
$requete->execute();
$infos = $requete->fetch(PDO::FETCH_ASSOC);

?>

<?php
require_once 'header.php'?>

<body>
    <nav>
        <?php include_once 'Menu_gestion_licence.php' ?>
    </nav>
    <section class="teacher-information page">
        <div class="breadcrumb">  
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Corps enseignant</p>
            <p>></p>
            <p><?php echo $infos["first_name"] ?></p>
            <p><?php echo $infos["last_name"] ?></p>
            <p>></p>
            <p>Informations générales</p>
        </div>

        <section>
            <div class="align margin-null">
                <h3 class="margin-null"><span><?php echo $infos["first_name"] ?></span>
                <span><?php echo $infos["last_name"] ?></span></h3>
            </div>
            <p class="yellow-title">Modules enseignés</p>
            <div class="information-part">
            <?php
                $requete = $con->prepare("SELECT m.name, m.hours_count FROM instructor_module im JOIN module m ON im.module_id = m.id  WHERE im.instructor_id= :id ");
                $requete->bindParam(':id', $id);
                $requete->execute();
                $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

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
                <a href="" class="link-unselected">Informations générales</a>
                <a href=""  class="link-select">Interventions</a>
            </div>
            <div>
                <p class="yellow-title">Filtrer les interventions</p>
            </div>
            
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
