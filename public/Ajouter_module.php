<?php 
require_once 'inclus/auth_check.php';
require_once("inclus/Header.php")?>
<body>
    <nav>
        <?php $active='modules';
        require_once('inclus/menu_gestion_licence.php'); ?>
    </nav>

     <?php require_once 'inclus/connexion.php';

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
        <div class="breadcrumb"> <!-- Fil d'ariane -->
            <a href="calendrier.php"><img src="assets/home.png" alt=""></a>
            <p>></p>
            <a href="Liste_module.php">Modules</a>
        </div>
    
    <section class="intervention_sheet">
        <h3>Modules</h3> <!-- Titre de la page -->
        <form action="" method="post"> <!-- Formulaire d'ajout d'un module, les données sont envoyées en POST pour être traitées à la fin du fichier -->
            <div class="form-align">
                <div>
                    <label for="code">Code - champ obligatoire</label>
                    <input type="text" placeholder="Saisissez le code du module" id="code" name="code" required> <!-- Champ de saisie du code du module -->
                </div>
                <div>
                    <label for="name">Nom - champ obligatoire</label>
                    <input type="text" placeholder="Saisissez le nom du module" id="name" name="name" required> <!-- Champ de saisie du nom du module -->
                </div>
            </div>

            <div class="form-align">
                <div>
                    <label for="hours_count">Nombre d'heures</label>
                    <input type="number" placeholder="Saisissez le nombre d'heures" id="hours_count" name="hours_count" min="0"> <!-- Champ de saisie du nombre d'heures du module (pas obligatoire) -->
                </div>
                <div>
                    <label for="parent_id">Module parent</label>
                    <select id="parent_id" name="parent_id"> <!-- Champ de sélection du module parent (pas obligatoire) -->
                        <option value="">-- Aucun --</option> <!-- Option par défaut pour indiquer qu'il n'y a pas de module parent -->
                        <?php foreach ($modules as $module): ?> <!-- On prend 1 module par 1 module pour les afficher dans la liste déroulante -->
                            <option value="<?php echo $module['id']; ?>"  
                                <?php echo ($contenu['parent_id'] == $module['id']) ? 'selected' : ''; ?>> <!-- Si le module est le parent du module en cours d'édition, on le sélectionne par défaut dans la liste déroulante -->
                                <?php echo htmlspecialchars($module['code'] . ' - ' . $module['name']); ?> <!-- Affichage du code et du nom du module dans la liste déroulante pour faciliter le choix du parent -->
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="desc_form_update">
                <label for="description">Description</label>
                <input class="input_desc" type="text" placeholder="Saisissez la description du module" id="description" name="description"> <!-- Champ de saisie de la description du module (pas obligatoire) -->
            </div>

            <div>
                <input type="checkbox" id="red_string" name="red_string" value="1" />
                <label for="red_string">Module effectué sur le projet fil rouge</label> <!-- Case à cocher pour indiquer si le module est effectué sur le projet fil rouge (pas obligatoire) -->
            </div>

            <div class="button-intervention">
                <a href="modules.php" class="grey-button selection">Retour à la liste</a>
                <button type="submit" class="blue-button selection">Ajouter le module</button>
            </div>
        </form>
    </section>
</body>
</html>


<?php
// AJOUTER UN MODULE
if (!empty($_POST['code']) && !empty($_POST['name'])) { //On vérifie que les champs obligatoires sont remplis 
    $code = htmlspecialchars($_POST['code']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description'] ?? '');
    $hours_count = isset($_POST['hours_count']) ? (int) $_POST['hours_count'] : null; // On vérifie que le nombre d'heures est un entier, sinon on le considère comme null
    $red_string = isset($_POST['red_string']) ? (int) $_POST['red_string'] : 0; // On vérifie que la case à cocher est cochée, sinon on considère que le module n'est pas effectué sur le projet fil rouge
    $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null; // On vérifie que le parent_id est un entier, sinon on le considère comme null (pas de parent)

    $requete = $con->prepare(
        "INSERT INTO module (code, parent_id, name, description, hours_count, capstone_project) VALUES (:code, :parent_id, :name, :description, :hours_count, :red_string)");
    $requete->bindParam(':code', $code); //On lie le code du module à la requête préparée
    $requete->bindParam(':parent_id', $parent_id, \PDO::PARAM_INT);
    $requete->bindParam(':name',$name);
    $requete->bindParam(':description', $description);
    $requete->bindParam(':hours_count', $hours_count, \PDO::PARAM_INT);
    $requete->bindParam(':red_string', $red_string, \PDO::PARAM_INT);
    $requete->execute(); //On insère le nouveau module dans la base de données
}