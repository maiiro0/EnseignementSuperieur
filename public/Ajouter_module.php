<?php 
require_once 'inclus/auth_check.php';
require_once("inclus/Header.php")?>
<body>
    <nav>
        <?php require_once('inclus/Menu_gestion_licence.php'); ?>
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
                    <input type="text" id="code" name="code" required>
                </div>
                <div>
                    <label for="name">Nom - champ obligatoire</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>

            <div class="form-align">
                <div>
                    <label for="hours_count">Nombre d'heures</label>
                    <input type="number" id="hours_count" name="hours_count" min="0">
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

            <div class="form-align">
                <div>
                    <label for="capstone_project">Projet de fin d'études</label>
                    <select id="capstone_project" name="capstone_project">
                        <option value="0">Non</option>
                        <option value="1">Oui</option>
                    </select>
                </div>
            </div>

            <div class="desc_form_update">
                <label for="description">Description</label>
                <input class="input_desc" type="text" id="description" name="description">
            </div>
              <div class="button-intervention">
                <a href="Type_intervention.php" class="grey-button selection">Retour à la liste</a>
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
    $capstone_project = isset($_POST['capstone_project']) ? (int) $_POST['capstone_project'] : 0;
    $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

    $requete = $con->prepare(
        "INSERT INTO module (code, parent_id, name, description, hours_count, capstone_project) VALUES (:code, :parent_id, :name, :description, :hours_count, :capstone_project)");
    $requete->bindParam(':code', $code);
    $requete->bindParam(':parent_id', $parent_id, \PDO::PARAM_INT);
    $requete->bindParam(':name',$name);
    $requete->bindParam(':description', $description);
    $requete->bindParam(':hours_count', $hours_count, \PDO::PARAM_INT);
    $requete->bindParam(':capstone_project', $capstone_project, \PDO::PARAM_INT);
    $requete->execute();
}