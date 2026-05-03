<?php
require_once 'inclus/auth_check.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
require_once 'inclus/Connexion.php';
$active='types';

if (empty($_GET["page"])){
    $page = 1;
    $_GET["page"] = 1;
}
else {
    $page = $_GET['page'];
}


if (empty($_GET["filtre"])){
    $filtre = '';
    $_GET["filtre"] = "";
}
else {
    $filtre = $_GET["filtre"];
}
?>

<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="#">Types intervention</a>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Types intervention</h3>
                <a href="Ajout_type_intervention.php" class="blue-button">Ajouter un type</a>
            </div>

            <form method="get" action="">
                <h3 class="yellow">Filtres</h3>
                <div class="filter-row">
                    <div class="filter-column">
                        <label name="name-filter">Nom</label>
                        <input type="text" name="name-filter" placeholder="Saisissez le nom">
                    </div>
                    <button class="yellow-button">Filtrer</button>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr class="columns">
                        <td>Nom</td>
                        <td>Descriptif</td>
                        <td>Couleur</td>
                        <td></td>
                    </tr>
                </thead>
                <?php
                    if (!empty($_GET["name-filter"])){
                        $filtre = '%'.$_GET["name-filter"].'%';
                        $requete = $con->prepare("SELECT count(id) FROM intervention_type WHERE name LIKE :filtre");
                        $requete->bindParam(':filtre', $filtre);
                        $requete -> execute();
                        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
                        $contenu=(int)$contenu[0]["count(id)"];
                        echo "<h4>".$contenu." types</h4>";

                        $limit = 10;
                        $offset = $page * $limit - $limit;
                        $contenu = select_id_intervention_type_where($con, $filtre, $offset);
                        ?>
                        <tbody>
                            <?php
                            foreach ($contenu as $colonne => $element) {
                            echo "<tr>";
                            echo "<td>". $element["name"]. "</td>";
                            echo "<td>". $element["description"]. "</td>"; ?>
                            <td style="color:<?php echo $element["color"]?>"><?php echo $element["color"] ?></td>

                            <td class="table_align"><img src="assets/Oeil.png" alt="">
                            <a href="Fiche_intervention.php?id=<?php echo $element['id']; ?>">Accéder à la fiche</a></td>
                            </tr><?php
                            }
                            ?>
                        </tbody>
                        <?php
                        $nb_pages = select_nb_pages_filtre_intervention($con, $filtre);
                        $nb_pages = $nb_pages[0]["nblignes"];
                        $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                    }

                    else {
                        $requete = $con->prepare("SELECT count(id) FROM intervention_type");
                        $requete -> execute();
                        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
                        $contenu=(int)$contenu[0]["count(id)"];
                        echo "<h4>".$contenu." types</h4>";

                        $limit = 10;
                        $offset = $page * $limit - $limit;
                        $contenu = infos_intervention_type_all($con, $offset);
                        ?>
                        <tbody>
                            <?php
                            foreach ($contenu as $colonne => $element) {
                                echo "<tr>";
                                echo "<td>". $element["name"]. "</td>";
                                echo "<td>". $element["description"]. "</td>"; ?>
                                <td style="color:<?php echo $element["color"]?>"><?php echo $element["color"] ?></td>

                                <td class="table_align"><img src="assets/Oeil.png" alt="">
                                <a href="Fiche_intervention.php?id=<?php echo $element['id']; ?>">Accéder à la fiche</a></td>
                                <?php
                                echo "</tr>";
                            }
                        ?>
                        </tbody>
                        <?php
                        $nb_pages = select_nb_pages_filtre_intervention_all($con);
                        $nb_pages = $nb_pages[0]["nblignes"];
                        $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                    }
                    ?>
                </table>
                <?php 
                if ($_GET["page"] == 1 && $nb_pages == 1){ ?>
                <?php
                }
                else if ($_GET["page"] == $nb_pages){?>
                    <a href="Type_intervention.php?page=<?php echo $page - 1; ?>&filtre=<?php echo $filtre ?>">Page précédente </a><?php
                }
                else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?>
                    <a href="Type_intervention.php?page=<?php echo $page - 1; ?>&filtre=<?php echo $filtre ?>">Page précédente </a>
                    <a href="Type_intervention.php?page=<?php echo $page + 1; ?>&filtre=<?php echo $filtre ?>"> Page suivante</a>
                    <?php
                } 
                else { ?>
                    <a href="Type_intervention.php?page=<?php echo $page + 1; ?>&filtre=<?php echo $filtre ?>"> Page suivante</a><?php
                }
            ?>
        </section>
    </section>
</body>
</html>

<?php