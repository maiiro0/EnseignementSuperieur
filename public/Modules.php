<?php 
require_once 'inclus/auth_check.php';
require_once('inclus/Connexion.php');
require_once '../database/User_database.php';
require_once 'inclus/Header.php';
$active='modules';
?>

<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="#">Modules</a>
        </div>

        <section class="titles-page">
            <h3>Modules</h3>
        </section>

        <section class="dashed">
            <?php
                $infos = select_parent($con);
                ?><ul><?php
                foreach ($infos as $info){ ?>
                    <div class='form-align module'>
                        <li><img src="assets/Module-arrow.png" alt=""><?php echo $info['name'] . ' (' . $info['hours_count'] . 'h)'; ?> </li>
                        <a href="Fiche_module.php?id=<?php echo $info['id']; ?>">></a>
                    </div>
                    <?php
                    $requete = $con->prepare('SELECT id, name, hours_count FROM module WHERE parent_id = :nom_parent');
                    $requete->bindParam(':nom_parent', $info['id']);
                    $requete->execute();
                    $enfants = $requete->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($enfants as $enfant) { ?>
                        <div class='form-align module'>
                            <li><img src="assets/linestraight.png" alt=""><img src="assets/line.png" alt=""><img src="assets/Module-arrow.png" alt=""><?php echo $enfant['name'] . ' (' . $enfant['hours_count'] . 'h)'; ?> </li>
                            <a href="Fiche_module.php?id=<?php echo $enfant['id']; ?>">></a>
                        </div><?php

                        $requete = $con->prepare('SELECT id, name, hours_count FROM module WHERE parent_id = :nom_parent');
                        $requete->bindParam(':nom_parent', $enfant['id']);
                        $requete->execute();
                        $petitsenfants = $requete->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($petitsenfants as $petitenfant) { ?>
                            <div class='form-align module'>
                                <li><img src="assets/linestraight.png" alt=""><img src="assets/linestraight.png" alt=""><img src="assets/line.png" alt=""><img src="assets/Module-arrow.png" alt=""><?php echo $petitenfant['name'] . ' (' . $petitenfant['hours_count'] . 'h)'; ?> </li>
                                <a href="Fiche_module.php?id=<?php echo $petitenfant['id']; ?>">></a>
                            </div><?php
                        }
                    }
                }
                ?></ul><?php
            ?>
        </section>
        <a href="Ajouter_module.php" class="blue-button margin-top">Ajouter un module</a>
    </section>
</body>
</html>