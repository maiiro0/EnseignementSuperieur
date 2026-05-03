<?php 
require_once 'inclus/auth_check.php';
require_once("inclus/Header.php")?>
<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

     <?php 
    require_once 'inclus/Connexion.php';

    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $id = (int) $id;

        // Récupération du module 
        $requete = $con->prepare("SELECT code, name, description, hours_count, capstone_project, parent_id FROM module WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->execute();
        $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $contenu = $contenu[0];

        // Récupération de tous les modules pour le parent 
        $requeteModules = $con->prepare("SELECT id, code, name FROM module WHERE id != :id ORDER BY code ASC");
        $requeteModules->bindParam(':id', $id);
        $requeteModules->execute();
        $modules = $requeteModules->fetchAll(\PDO::FETCH_ASSOC);
    }
    ?>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Liste_module.php">Modules</a>
            <p>></p>
            <p>Fiche module</p>
            <p>></p>
            <p><?php echo htmlspecialchars($contenu['name']); ?></p>
        </div>
    
    <section class="intervention_sheet">
        <h3><?php echo htmlspecialchars($contenu['name']); ?></h3>
        <form action="" method="post">
            <div class="form-align">
                <div>
                    <label for="code">Code - champ obligatoire</label>
                    <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($contenu['code']); ?>" required>
                </div>
                <div>
                    <label for="name">Nom - champ obligatoire</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($contenu['name']); ?>" required>
                </div>
            </div>

            <div class="form-align">
                <div>
                    <label for="hours_count">Nombre d'heures</label>
                    <input type="number" id="hours_count" name="hours_count" min="0" value="<?php echo htmlspecialchars($contenu['hours_count']); ?>">
                </div>
                <div>
                    <label for="parent_id">Module parent</label>
                    <select id="parent_id" name="parent_id">
                        <option value="">-- Aucun --</option>
                        <?php foreach ($modules as $module): ?>
                            <option value="<?php echo $module['id']; ?>" 
                                <?php echo ($contenu['parent_id'] == $module['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($module['code'] . ' - ' . $module['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="desc_form_update">
                <label for="description">Description</label>
                <input class="input_desc" type="text" id="description" name="description" value="<?php echo htmlspecialchars($contenu['description']); ?>">
            </div>
              <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
                <button type="button" command="show-modal" commandfor="supp" class="red-button selection">Supprimer</button>
                <button type="submit" class="blue-button selection">Enregistrer les informations</button>
            </div>
        </form>


        <dialog id="supp">
            <button commandfor="supp" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button>
            <div class="add-intervention">
                <img src="assets/Croix.png" alt="">
                <div>
                    <h3>Supprimer le type d'intervention</h3>
                    <p>Confirmation de l'action</p>
                </div>
            </div>
            <div>
                <div>
                    <p>Vous vous apprêtez à supprimer le type d'intervention,</p>
                    <p>cette action est irrévoquable.</p>
                    <p>A noter qu'aucune intervention de doit être liée à ce module pour pouvoir le supprimer.</p>
                    <br>
                    <p>Confirmez-vous l'action ?</p>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="pass">
                    <div class="button-form">
                        <button type="submit" class="grey-button selection" commandfor="supp" command="close">Annuler</button>
                        <button class="red-button selection" type="submit" name="action" value="confirm-delete">Confirmer</button>  
                    </div>
                </form>
            </div>
        </dialog>
    </section>
</body>
</html>


<?php
// SUPPRESSION
if (isset($_POST['action']) && $_POST['action'] === 'confirm-delete') {

    // Vérification : aucun cours lié à ce module
    $requete = $con->prepare("SELECT id FROM course WHERE module_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $coursLies = $requete->fetchAll(\PDO::FETCH_ASSOC);

    if (empty($coursLies)) {
        // Suppression des liaisons instructeur-module
        $requete = $con->prepare("DELETE FROM instructor_module WHERE module_id = :id");
        $requete->bindParam(':id', $id);
        $requete->execute();

        // Suppression du module
        $requete = $con->prepare("DELETE FROM module WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->execute();

        header('Location: Liste_module.php');
        exit;
    } else {
        $erreurSuppression = "Impossible de supprimer ce module : des cours y sont encore liés.";
    }
}

// MISE À JOUR 
if (!empty($_POST['code']) &&  !empty($_POST['name']) &&  isset($_POST['hours_count']) && isset($_POST['capstone_project'])) {
    
    $code = htmlspecialchars($_POST['code']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description'] ?? '');
    $hours_count = (int) $_POST['hours_count'];
    $capstone_project = (int) $_POST['capstone_project'];
    $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

    $requete = $con->prepare(
        "UPDATE module 
         SET code = :code, name = :name, description = :description, 
             hours_count = :hours_count, capstone_project = :capstone_project, 
             parent_id = :parent_id 
         WHERE id = :id"
    );
    $requete->bindParam(':id', $id);
    $requete->bindParam(':code', $code);
    $requete->bindParam(':name',$name);
    $requete->bindParam(':description', $description);
    $requete->bindParam(':hours_count', $hours_count, \PDO::PARAM_INT);
    $requete->bindParam(':capstone_project', $capstone_project, \PDO::PARAM_INT);
    $requete->bindParam(':parent_id', $parent_id, \PDO::PARAM_INT);
    $requete->execute();
}