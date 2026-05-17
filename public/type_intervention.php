<?php
require_once 'inclus/auth_check.php';
require_once 'inclus/Header.php';
require_once '../database/User_database.php';
require_once 'inclus/connexion.php';
$active='types';

if (empty($_GET["page"])){ // Vérification de la pagination dans l'URL. Si pas de numéro de page, on considère que c'est la page 1 par défaut
    $page = 1;
    $_GET["page"] = 1;
}
else { // Récupère le numéro de la page dans l'URL
    $page = $_GET['page'];
}


if (empty($_GET["filtre"])){ // Vérification que le filtre est présent dans l'URL
    $filtre = '';
    $_GET["filtre"] = "";
}
else {
    $filtre = $_GET["filtre"];
}
?>

<body>
    <nav>
        <?php require_once('inclus/menu_gestion_licence.php'); ?> 
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb"> <!-- Fil d'ariane -->
            <a href="calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="#">Types intervention</a>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Types intervention</h3>
                <a href="ajout_type_intervention.php" class="blue-button">Ajouter un type</a>
            </div>

            <form method="get" action="">
                <h3 class="yellow">Filtres</h3> <!-- Titre de la section des filtres -->
                <div class="filter-row">
                    <div class="filter-column">
                        <label name="name-filter">Nom</label>
                        <input type="text" name="name-filter" placeholder="Saisissez le nom"> 
                    </div>
                    <button class="yellow-button">Filtrer</button>
                </div>
            </form>

            <table class="table"> <!-- Tableau des interventions -->
                <thead>
                    <tr class="columns">
                        <td>Nom</td>
                        <td>Descriptif</td>
                        <td>Couleur</td>
                        <td></td>
                    </tr>
                </thead>
                <?php
                    if (!empty($_GET["name-filter"])){ // Vérification que le filtre est bien dans l'URL
                        $filtre = '%'.$_GET["name-filter"].'%'; // Ajout de % avant et après le filtre pour pouvoir faire une recherche avec LIKE dans la base de données, cela permet de trouver tous les types d'intervention dont le nom contient le texte saisi dans le filtre
                        $requete = $con->prepare("SELECT count(id) FROM intervention_type WHERE name LIKE :filtre"); 
                        $requete->bindParam(':filtre', $filtre);
                        $requete -> execute();
                        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
                        $contenu=(int)$contenu[0]["count(id)"]; // Récupération du nombre de types d'intervention correspondant au filtre pour pouvoir faire la pagination, les types d'intervention sont filtrés par leur nom, les informations récupérées sont le nombre de types d'intervention correspondant au filtre
                        echo "<h4>".$contenu." types</h4>"; // Affichage du nombre de types d'intreventuion correspondant au filtre

                        $limit = 10;
                        $offset = $page * $limit - $limit;
                        $contenu = select_id_intervention_type_where($con, $filtre, $offset); // Récupération des types d'intervention correspondant au filtre pour pouvoir les afficher dans le tableau, les types d'intervention sont filtrés par leur nom, les informations récupérées sont le nom du type d'intervention, sa description, sa couleur et son id pour pouvoir accéder à la fiche de ce type d'intervention
                        ?>
                        <tbody>
                            <?php
                            foreach ($contenu as $colonne => $element) { // On prend 1 type d'intervention par 1 type d'intervention pour les afficher dans le tableau
                            echo "<tr>";
                            echo "<td>". $element["name"]. "</td>";
                            echo "<td>". $element["description"]. "</td>"; ?>
                            <td style="color:<?php echo $element["color"]?>"><?php echo $element["color"] ?></td> <!-- Affichage du code couleur du type d'intervention, le texte du code couleur est affiché dans la couleur correspondante pour différencier les types d'intervention par leur couleur -->

                            <td class="table_align"><img src="assets/Oeil.png" alt="">
                            <a href="fiche_intervention.php?id=<?php echo $element['id']; ?>">Accéder à la fiche</a></td>
                            </tr><?php
                            }
                            ?>
                        </tbody>
                        <?php
                        $nb_pages = select_nb_pages_filtre_intervention($con, $filtre);
                        $nb_pages = $nb_pages[0]["nblignes"];
                        $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1;
                    }

                    else { // Sinon, affichage de tous les types d'intervention sans filtrer, avec la même pagination que précédemment
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
                                <td style="color:<?php echo $element["color"]?>"><?php echo $element["color"] ?></td> <!-- Affichage du code couleur du type d'intervention, le texte du code couleur est affiché dans la couleur correspondante pour différencier les types d'intervention par leur couleur -->

                                <td class="table_align"><img src="assets/Oeil.png" alt="">
                                <a href="fiche_intervention.php?id=<?php echo $element['id']; ?>">Accéder à la fiche</a></td> <!-- Renvoie à fiche_intervention.php pour avoir des précisions sur le type d'intervention-->
                                <?php
                                echo "</tr>";
                            }
                        ?>
                        </tbody>
                        <?php
                        $nb_pages = select_nb_pages_filtre_intervention_all($con); // Récupération du nombre total de types d'intervention pour pouvoir faire la pagination, les informations récupérées sont le nombre de types d'intervention
                        $nb_pages = $nb_pages[0]["nblignes"];
                        $nb_pages = $nb_pages = (int)($nb_pages / 10) + 1; 
                    }
                    ?>
                </table>
                <?php 
                if ($_GET["page"] == 1 && $nb_pages == 1){ ?> <!-- Si il n'y a qu'une seule page, on n'affiche pas les liens de pagination -->
                    <?php
                }
                else if ($_GET["page"] == 1){ ?> <!-- Si on est sur la première page, on n'affiche pas le lien de la page précédente -->
                    <a href="type_intervention.php?page=<?php echo $page + 1; ?>&filtre=<?php echo $filtre ?>"> Page suivante</a> <!-- Lien vers la page suivante en oubliant pas le filtre -->
                    <?php
                }
                else if ($_GET["page"] == $nb_pages){?>
                    <a href="type_intervention.php?page=<?php echo $page - 1; ?>&filtre=<?php echo $filtre ?>">Page précédente </a> <!-- Lien vers la page précédente en oubliant pas le filtre -->
                    <?php
                }
                else if ($_GET["page"] > 1 && $_GET["page"] < $nb_pages){ ?>
                    <a href="type_intervention.php?page=<?php echo $page - 1; ?>&filtre=<?php echo $filtre ?>">Page précédente </a> <!-- Lien vers la page précédente en oubliant pas le filtre -->
                    <a href="type_intervention.php?page=<?php echo $page + 1; ?>&filtre=<?php echo $filtre ?>"> Page suivante</a> <!-- Lien vers la page suivante en oubliant pas le filtre -->
                    <?php
                } 
                else { ?>
                    <a href="type_intervention.php?page=<?php echo $page + 1; ?>&filtre=<?php echo $filtre ?>"> Page suivante</a> <!-- Lien vers la page suivante en oubliant pas le filtre --><?php
                }
            ?>
        </section>
    </section>
</body>
</html>

<?php
