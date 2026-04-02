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
            <a href="#">Types intervention</a>
        </div>

        <section class="titles-page">
            <h3>Types intervention</h3>
        </section>

        <section class="dashed">
            <?php
                $infos = select_parent($con);
                ?><ul><?php
                foreach ($infos as $info){ ?>
                    <li><img src="assets/Module-arrow.png" alt=""><?php echo $info['name']; ?> </li>
                    <?php
                    $requete = $con->prepare('SELECT id, name FROM module WHERE parent_id = :nom_parent');
                    $requete->bindParam(':nom_parent', $info['id']);
                    $requete->execute();
                    $enfants = $requete->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($enfants as $enfant) {?>
                        <li><img src="" alt=""><?php echo $enfant['name']; ?> </li><?php
                    }
                }
                ?></ul><?php
            ?>
        </section>
    </section>
</body>
</html>