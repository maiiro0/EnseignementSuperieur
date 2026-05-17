<?php 
require_once 'inclus/auth_check.php';
require_once('inclus/connexion.php');
require_once '../database/User_database.php';
require_once 'inclus/Header.php';
$active='modules';
?>

<body>
    <nav>
        <?php require_once('inclus/menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <a href="calendrier.php"><img src="assets/home.png" alt=""></a> <!-- Fil d'ariane -->
            <p>></p>
            <a href="#">Modules</a>
        </div>

        <section class="titles-page"> <!-- Section pour le titre de la page -->
            <h3>Modules</h3>
        </section>

        <section class="dashed">
            <?php
                $infos = select_parent($con); // Récupération des modules parents
                ?><ul><?php
                foreach ($infos as $info){ ?> <!-- On prend 1 module parent par 1 module parent pour les afficher -->
                    <div class='form-align module'>
                        <li><img src="assets/Module-arrow.png" alt=""><?php echo $info['name'] . ' (' . $info['hours_count'] . 'h)'; ?> </li> <!-- Affichage du module parent avec le nombre d'heures associées -->
                        <a href="fiche_module.php?id=<?php echo $info['id']; ?>">></a>
                    </div>
                    <?php
                    $requete = $con->prepare('SELECT id, name, hours_count FROM module WHERE parent_id = :nom_parent'); // Récupération des modules enfants du module parent
                    $requete->bindParam(':nom_parent', $info['id']);
                    $requete->execute();
                    $enfants = $requete->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($enfants as $enfant) { ?> <!-- On prend 1 enfant par 1 enfant pour les afficher à la suite de leur parent -->
                        <div class='form-align module'>
                            <li><img src="assets/linestraight.png" alt=""><img src="assets/line.png" alt=""><img src="assets/Module-arrow.png" alt=""><?php echo $enfant['name'] . ' (' . $enfant['hours_count'] . 'h)'; ?> </li> <!-- Affichage du module enfant avec le nombre d'heures associées -->
                            <a href="fiche_module.php?id=<?php echo $enfant['id']; ?>">></a>
                        </div><?php

                        $requete = $con->prepare('SELECT id, name, hours_count FROM module WHERE parent_id = :nom_parent'); // Récupération des modules petits-enfants du module enfant
                        $requete->bindParam(':nom_parent', $enfant['id']);
                        $requete->execute();
                        $petitsenfants = $requete->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($petitsenfants as $petitenfant) { ?> <!-- On prend 1 petit enfant par 1 petit enfant pour les afficher à la suite de leur parent -->
                            <div class='form-align module'> 
                                <li><img src="assets/linestraight.png" alt=""><img src="assets/linestraight.png" alt=""><img src="assets/line.png" alt=""><img src="assets/Module-arrow.png" alt=""><?php echo $petitenfant['name'] . ' (' . $petitenfant['hours_count'] . 'h)'; ?> </li> <!-- Affichage du module petit-enfant avec le nombre d'heures associées - les images sont les indentations -->
                                <a href="fiche_module.php?id=<?php echo $petitenfant['id']; ?>">></a>
                            </div><?php
                        }
                    }
                }
                ?></ul><?php
            ?>
        </section>
        <a href="ajouter_module.php" class="blue-button margin-top">Ajouter un module</a> <!-- Bouton pour accéder à la page d'ajout d'un module (ajouter_module.php) -->
    </section>
</body>
</html>