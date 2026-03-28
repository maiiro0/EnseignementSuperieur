<?php
require_once 'header.php'?>

<body>
    <nav>
        <?php require_once('Menu_gestion_licence.php'); 
        require_once '../database/User_database.php';?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Types intervention</p>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Types intervention</h3>
                <a href="Ajout_intervention.php" class="blue-button">Ajouter un type</a>
            </div>

            <form method="post" action="">
                <h3 class="yellow">Filtres</h3>
                <div class="intervention-row">
                    <div class="intervention-column">
                        <label name="name-filter">Nom</label>
                        <input type="text" name="name-filter" placeholder="Saisissez le nom">
                    </div>
                    <button class="yellow-button">Filtrer</button>
                </div>
            </form>

            <?php
            require_once 'connexion.php';

            $requete = $con->prepare("SELECT count(id) FROM intervention_type");
            $requete -> execute();
            $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
            $contenu=(int)$contenu[0]["count(id)"];
            echo "<h4>".$contenu." types</h4>";
            ?>

            <table class="table">
                <tr class="columns">
                    <td>Nom</td>
                    <td>Descriptif</td>
                    <td>Couleur</td>
                    <td></td>
                </tr>
                <?php
                    if (!empty($_POST["name-filter"])){
                        $filtre = $_POST["name-filter"];
                        $contenu = select_id_intervention_type_where($con, $filtre);
                        
                        foreach ($contenu as $colonne => $element) {
                        echo "<tr>";
                        echo "<td>". $element["name"]. "</td>";
                        echo "<td>". $element["description"]. "</td>"; ?>
                        <td style="color:<?php echo $element["color"]?>"><?php echo $element["color"] ?></td>

                        <td class="table_align"><img src="assets/Oeil.png" alt="">
                        <a href="Fiche_intervention.php?id=<?php echo $element['id']; ?>">Accéder à la fiche</a></td>
                        </tr><?php
                        }
                    }

                    else {
                        $contenu = infos_intervention_type_all($con);

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
                    }
                ?>

            </table>
        </section>
    </section>
</body>
</html>

<?php