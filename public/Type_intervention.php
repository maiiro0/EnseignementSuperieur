<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types Intervention</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <?php require_once('Menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type">
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
                        $requete = $con->prepare("SELECT id, name, description, color FROM intervention_type WHERE name=:filtre");
                        $requete->bindParam(':filtre', $filtre);
                        $requete->execute();
                        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
                        
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
                        $requete = $con->prepare("SELECT id, name, description, color FROM intervention_type");
                        $requete->execute();
                        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

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