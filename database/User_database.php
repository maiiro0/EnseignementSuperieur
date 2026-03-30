<?php

function infos_intervention_type($con, $id){
    $requete = $con->prepare("SELECT name, description, color FROM intervention_type WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu[0];
}

function infos_intervention_type_all($con, $offset){
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT id, name, description, color FROM intervention_type LIMIT :offsetend OFFSET :offset");
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function id_course($id, $con){
    $requete = $con->prepare("SELECT id FROM course WHERE intervention_type_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $multi_id = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $multi_id;
}

function delete_course_instructor($con, $valeurs){
    $requete = $con->prepare("DELETE FROM course_instructor WHERE course_id = :multi_id");
    $requete->bindParam(':multi_id', $valeurs["id"]);
    $requete->execute();
}

function delete_course($con, $id){
    $requete = $con->prepare("DELETE FROM course WHERE intervention_type_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
}

function delete_intervention_type($con, $id){
    $requete = $con->prepare("DELETE FROM intervention_type WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
}

function update_intervention_type($con, $id, $name, $color, $description){
    $requete = $con->prepare("UPDATE intervention_type SET name = :name, color = :color, description = :description WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->bindParam(':name', $name);
    $requete->bindParam(':color', $color);
    $requete->bindParam(':description', $description);
    $requete->execute();
}

function insert_user($con, $role, $email, $last_name, $first_name){
    $requete = $con->prepare("INSERT INTO user (role, email, last_name, first_name) VALUES (:role, :email, :last_name, :first_name)");
    $requete->bindParam(':role', $role);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->execute();
}

function select_id_user_where($con, $role, $email, $last_name, $first_name){
    $requete = $con->prepare("SELECT id FROM user WHERE role = :role AND email=:email AND last_name=:last_name AND first_name=:first_name");
    $requete->bindParam(':role', $role);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->execute();
    $id = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $id;
}

function insert_instructor($con, $id) {
    $requete = $con->prepare("INSERT INTO instructor (user_id) VALUES (:id)");
    $requete ->bindParam(':id', $id[0]["id"]);
    $requete->execute();
}

function select_id_instructor($con, $id){
    $requete = $con->prepare("SELECT id FROM instructor WHERE user_id = :user");
    $requete->bindParam(':user', $id[0]["id"]);
    $requete->execute();
    $id = $requete->fetch(\PDO::FETCH_ASSOC);
    return $id;
}

function select_id_module($con, $modules){
    $requete = $con->prepare("SELECT id FROM module WHERE name=:name");
    $requete->bindParam(':name', $modules);
    $requete->execute();
    $module_name = $requete->fetch(\PDO::FETCH_ASSOC);
    return $module_name;
}

function insert_instructor_module($con, $module_name, $id){
    $requete = $con->prepare("INSERT INTO instructor_module (instructor_id, module_id) VALUES (:instructor_id, :module_id)");
    $requete->bindParam(':instructor_id', $id);
    $requete->bindParam(':module_id', $module_name);
    $requete->execute();
}

function select_name_module($con) {
    $requete = $con->prepare("SELECT m.name FROM  module m;");
    $requete->execute();
    $nom_module = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module;
}

function nom_module_where_instructor($con, $id){
    $requete = $con->prepare("SELECT m.name FROM  module m JOIN instructor_module im ON m.id = im.module_id WHERE im.instructor_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $nom_module_selected = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module_selected;
}

function infos_module_where($con, $filtre_prenom, $filtre_nom, $filtre_email, $offset){
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count,  u.id FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id WHERE u.first_name LIKE :first_name OR u.last_name LIKE :last_name OR u.email LIKE :email LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':first_name', $filtre_prenom);
    $requete->bindParam(':last_name', $filtre_nom);
    $requete->bindParam(':email', $filtre_email);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_infos_table_corps_enseignant($con, $offset){
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count, u.id FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id LIMIT :offsetend OFFSET :offset");
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function insert_intervention_type($con, $name, $color, $description) {
    $requete = $con->prepare("INSERT INTO intervention_type (name, description, color) VALUES (:name, :description, :color);");
    $requete->bindParam(':name', $name);
    $requete->bindParam(':color', $color);
    $requete->bindParam(':description', $description);
    $requete->execute();
}

function select_id_intervention_type_where($con, $filtre, $offset) {
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT id, name, description, color FROM intervention_type WHERE name LIKE :filtre LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':filtre', $filtre);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_entier($con){
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_filtre($con, $filtre_prenom, $filtre_nom, $filtre_email){
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id WHERE u.first_name LIKE :first_name OR u.last_name LIKE :last_name OR u.email LIKE :email");
    $requete->bindParam(':first_name', $filtre_prenom);
    $requete->bindParam(':last_name', $filtre_nom);
    $requete->bindParam(':email', $filtre_email);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_filtre_intervention($con, $filtre){
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM intervention_type WHERE name=:filtre");
    $requete->bindParam(':filtre', $filtre);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_filtre_intervention_all($con){
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM intervention_type");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_infos_enseignant($con, $id){
    $requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $infos = $requete->fetch(PDO::FETCH_ASSOC);
    return $infos;
}


function select_infos_modules_enseignant($con, $id){
    $requete = $con->prepare("SELECT m.name, m.hours_count FROM instructor_module im JOIN module m ON im.module_id = m.id  WHERE im.instructor_id= :id ");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_modules_corp_enseignant($con){
    $requete = $con->prepare("SELECT m.name FROM  module m;");
    $requete->execute();
    $nom_module = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module;
}

function select_modules_enseignées($con, $id){
    $requete = $con->prepare("SELECT m.name FROM  module m JOIN instructor_module im ON m.id = im.module_id WHERE im.instructor_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $nom_module_selected = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module_selected;
}

function update_infos_enseignant($con, $id, $last_name, $first_name, $email, $name){
    $requete = $con->prepare("UPDATE user SET last_name = :last_name, first_name = :first_name, email = :email WHERE id=:id");
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':id', $id);
    $requete->execute();

    
    
    $requete = $con->prepare("DELETE FROM instructor_module WHERE instructor_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();

    foreach ($name as $colonne => $element) {
        $requete = $con->prepare("SELECT id FROM module WHERE name = :element");
        $requete->bindParam(':element', $element);
        $requete->execute();
        $module_id = $requete->fetch(PDO::FETCH_ASSOC);

        $requete = $con->prepare("INSERT INTO instructor_module VALUES (:id ,:module_id)");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':module_id', $module_id['id']);
        $requete->execute();
    

    }
}

function filtre_fiche_enseignant($con, $id,  $filtre_start_date, $filtre_end_date, $filtre_name, $offset){
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT c.id, c.start_date, c.end_date, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id WHERE u.id = :id AND ( c.start_date LIKE :inter_start_date OR c.end_date LIKE :inter_end_date OR m.name LIKE :module_name) LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':id', $id);
    $requete->bindParam(':inter_start_date', $filtre_start_date);
    $requete->bindParam(':inter_end_date', $filtre_end_date);
    $requete->bindParam(':module_name', $filtre_name);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function fiche_enseignant_tableau_intervenants($con, $element ){
    $requete = $con->prepare("SELECT upper(u.last_name), upper(u.first_name) FROM user u join instructor i ON i.user_id = u.id join course_instructor ci ON ci.instructor_id = i.id WHERE ci.course_id = :id");
    $requete -> bindParam(':id', $element); 
    $requete->execute();
    $noms_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $noms_intervenants;
}

function fiche_enseignant_tableau($con, $id ,$offset){
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT c.id, c.start_date, c.end_date, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id WHERE u.id = :id LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':id', $id);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}


function select_nb_pages_filtre_fiche_enseignant($con, $id, $filtre_start_date, $filtre_end_date, $filtre_name){
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id WHERE u.id = :id AND ( c.start_date LIKE :inter_start_date OR c.end_date LIKE :inter_end_date OR m.name LIKE :module_name )");
    $requete->bindParam(':id', $id);
    $requete->bindParam(':inter_start_date', $filtre_start_date);
    $requete->bindParam(':inter_end_date', $filtre_end_date);
    $requete->bindParam(':module_name', $filtre_name);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}


function calendrier_tableau($con, $offset){
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT DISTINCT c.id, c.start_date, c.end_date, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id LIMIT :offsetend OFFSET :offset");
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_calendrier($con){
    $requete = $con->prepare("SELECT DISTINCT count(*) AS nblignes FROM course c ");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}
