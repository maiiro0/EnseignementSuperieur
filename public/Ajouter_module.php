<?php 
require_once 'inclus/auth_check.php';
require_once("inclus/Header.php")?>
<body>
    <nav>
        <?php $active='modules';
        require_once('inclus/Menu_gestion_licence.php'); ?>
    </nav>

     <?php require_once 'inclus/Connexion.php';

    // Récupération du module 
    $requete = $con->prepare("SELECT code, name, description, hours_count, capstone_project, parent_id FROM module");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    $contenu = $contenu[0];

    // Récupération de tous les modules pour le parent 
    $requeteModules = $con->prepare("SELECT id, code, name FROM module ORDER BY code ASC");
    $requeteModules->execute();
    $modules = $requeteModules->fetchAll(\PDO::FETCH_ASSOC);
    ?>

    <section class="intervention-type page">
        <div class="breadcrumb">
            <a href="Calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Liste_module.php">Modules</a>
        </div>
    
    <section class="intervention_sheet">
        <h3>Modules</h3>
        <form action="" method="post">
            <div class="form-align">
                <div>
                    <label for="code">Code - champ obligatoire</label>
                    <input type="text" placeholder="Saisissez le code du module" id="code" name="code" required>
                </div>
                <div>
                    <label for="name">Nom - champ obligatoire</label>
                    <input type="text" placeholder="Saisissez le nom du module" id="name" name="name" required>
                </div>
            </div>

            <div class="form-align">
                <div>
                    <label for="hours_count">Nombre d'heures</label>
                    <input type="number" placeholder="Saisissez le nombre d'heures" id="hours_count" name="hours_count" min="0">
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
                <input class="input_desc" type="text" placeholder="Saisissez la description du module" id="description" name="description">
            </div>

            <div>
                <input type="checkbox" id="red_string" name="red_string" value="1" />
                <label for="red_string">Module effectué sur le projet fil rouge</label>
            </div>

            <div class="button-intervention">
                <a href="Modules.php" class="grey-button selection">Retour à la liste</a>
                <button type="submit" class="blue-button selection">Ajouter le module</button>
            </div>
        </form>
    </section>
</body>
</html>


<?php
// AJOUTER UN MODULE
if (!empty($_POST['code']) && !empty($_POST['name'])) {
    $code = htmlspecialchars($_POST['code']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description'] ?? '');
    $hours_count = isset($_POST['hours_count']) ? (int) $_POST['hours_count'] : null;
    $red_string = isset($_POST['red_string']) ? (int) $_POST['red_string'] : 0;
    $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null; 

    $requete = $con->prepare(
        "INSERT INTO module (code, parent_id, name, description, hours_count, capstone_project) VALUES (:code, :parent_id, :name, :description, :hours_count, :red_string)");
    $requete->bindParam(':code', $code);
    $requete->bindParam(':parent_id', $parent_id, \PDO::PARAM_INT);
    $requete->bindParam(':name',$name);
    $requete->bindParam(':description', $description);
    $requete->bindParam(':hours_count', $hours_count, \PDO::PARAM_INT);
    $requete->bindParam(':red_string', $red_string, \PDO::PARAM_INT);
    $requete->execute();

    //MAJ des heures du parent
    if ($parent_id) {
        $requete = $con->prepare("UPDATE module SET hours_count = hours_count + :hours_count WHERE id = :parent_id");
        $requete->bindParam(':hours_count', $hours_count, \PDO::PARAM_INT);
        $requete->bindParam(':parent_id', $parent_id, \PDO::PARAM_INT);
        $requete->execute();
    }
}