<?php

function infos_intervention_type($con, $id){ // Récupération des informations d'un type d'intervention à partir de son id
    $requete = $con->prepare("SELECT name, description, color FROM intervention_type WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu[0];
}

function infos_intervention_type_all($con, $offset){ // Récupération de tous les types d'intervention avec une pagination de 10 types par page
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT id, name, description, color FROM intervention_type ORDER BY name ASC LIMIT :offsetend OFFSET :offset");
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function id_course($id, $con){ // Récupération de tous les id des cours associés à un type d'intervention à partir de l'id du type d'intervention
    $requete = $con->prepare("SELECT intervention_type_id FROM course WHERE intervention_type_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $multi_id = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $multi_id;
}

function delete_course_instructor($con, $valeurs){ // Suppression de tous les cours associés à un type d'intervention à partir de l'id du type d'intervention
    $requete = $con->prepare("DELETE FROM course_instructor WHERE course_id = :multi_id");
    $requete->bindParam(':multi_id', $valeurs["id"]);
    $requete->execute();
}

function delete_course($con, $id){ // Suppression de tous les cours associés à un type d'intervention à partir de l'id du type d'intervention
    $requete = $con->prepare("DELETE FROM course WHERE intervention_type_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
}

function delete_intervention_type($con, $id){ // Suppression d'un type d'intervention à partir de son id, avant de supprimer le type d'intervention, on supprime tous les cours associés à ce type d'intervention et tous les liens entre ces cours et les intervenants
    $requete = $con->prepare("DELETE FROM intervention_type WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
}

function update_intervention_type($con, $id, $name, $color, $description){ // Mise à jour des informations d'un type d'intervention à partir de son id, on peut mettre à jour le nom, la couleur et la description du type d'intervention
    $requete = $con->prepare("UPDATE intervention_type SET name = :name, color = :color, description = :description WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->bindParam(':name', $name);
    $requete->bindParam(':color', $color);
    $requete->bindParam(':description', $description);
    $requete->execute();
}

function insert_user($con, $role, $email, $last_name, $first_name){ // Insertion d'un nouvel utilisateur dans la base de données, on doit préciser son rôle, son email, son nom et son prénom
    $requete = $con->prepare("INSERT INTO user (role, email, last_name, first_name) VALUES (:role, :email, :last_name, :first_name)");
    $requete->bindParam(':role', $role);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->execute();
}

function select_id_user_where($con, $role, $email, $last_name, $first_name){ // Récupération de l'id d'un utilisateur à partir de son rôle, son email, son nom et son prénom, cette fonction est utilisée après l'insertion d'un nouvel utilisateur pour récupérer son id et pouvoir l'utiliser pour insérer un nouvel intervenant dans la table instructor
    $requete = $con->prepare("SELECT id FROM user WHERE role = :role AND email=:email AND last_name=:last_name AND first_name=:first_name");
    $requete->bindParam(':role', $role);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':last_name', $last_name);
    $requete->bindParam(':first_name', $first_name);
    $requete->execute();
    $id = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $id;
}

function insert_instructor($con, $id) { // Insertion d'un nouvel intervenant dans la base de données, on doit préciser l'id de l'utilisateur associé à cet intervenant, cette fonction est utilisée après l'insertion d'un nouvel utilisateur pour insérer un nouvel intervenant dans la table instructor
    $requete = $con->prepare("INSERT INTO instructor (user_id) VALUES (:id)");
    $requete ->bindParam(':id', $id[0]["id"]);
    $requete->execute();
}

function select_id_instructor($con, $id){ // Récupération de l'id d'un intervenant à partir de l'id de l'utilisateur associé à cet intervenant, cette fonction est utilisée après l'insertion d'un nouvel intervenant pour récupérer son id et pouvoir l'utiliser pour insérer les liens entre cet intervenant et les modules qu'il enseigne dans la table instructor_module
    $requete = $con->prepare("SELECT id FROM instructor WHERE user_id = :user");
    $requete->bindParam(':user', $id[0]["id"]);
    $requete->execute();
    $id = $requete->fetch(\PDO::FETCH_ASSOC);
    return $id;
}

function select_id_module($con, $modules){ // Récupération de l'id d'un module à partir de son nom, cette fonction est utilisée après l'insertion d'un nouvel intervenant pour récupérer l'id des modules qu'il enseigne et pouvoir les utiliser pour insérer les liens entre cet intervenant et les modules qu'il enseigne dans la table instructor_module
    $requete = $con->prepare("SELECT id FROM module WHERE name=:name");
    $requete->bindParam(':name', $modules);
    $requete->execute();
    $module_name = $requete->fetch(\PDO::FETCH_ASSOC);
    return $module_name;
}

function insert_instructor_module($con, $module_name, $id){ // Insertion des liens entre un intervenant et les modules qu'il enseigne dans la table instructor_module, on doit préciser l'id de l'intervenant et l'id du module, cette fonction est utilisée après l'insertion d'un nouvel intervenant pour insérer les liens entre cet intervenant et les modules qu'il enseigne dans la table instructor_module
    $requete = $con->prepare("INSERT INTO instructor_module (instructor_id, module_id) VALUES (:instructor_id, :module_id)");
    $requete->bindParam(':instructor_id', $id);
    $requete->bindParam(':module_id', $module_name);
    $requete->execute();
}

function select_name_module($con) { // Récupération de tous les noms des modules, cette fonction est utilisée pour afficher la liste des modules dans le formulaire d'ajout ou de modification d'un intervenant pour permettre à l'utilisateur de sélectionner les modules que l'intervenant enseigne
    $requete = $con->prepare("SELECT m.name FROM  module m;");
    $requete->execute();
    $nom_module = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module;
}

function nom_module_where_instructor($con, $id){ // Récupération de tous les noms des modules enseignés par un intervenant à partir de l'id de cet intervenant, cette fonction est utilisée pour afficher la liste des modules enseignés par un intervenant dans la fiche de cet intervenant et pour pré-sélectionner les modules enseignés par cet intervenant dans le formulaire de modification de cet intervenant
    $requete = $con->prepare("SELECT m.name FROM  module m JOIN instructor_module im ON m.id = im.module_id WHERE im.instructor_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $nom_module_selected = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module_selected;
}

function infos_module_where($con, $filtre_prenom, $filtre_nom, $filtre_email, $offset){ // Récupération de tous les modules enseignés par les intervenants qui correspondent aux filtres de recherche, avec une pagination de 10 modules par page, cette fonction est utilisée pour afficher la liste des modules enseignés par les intervenants qui correspondent aux filtres de recherche dans la page de gestion des intervenants
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count,  u.id FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id WHERE u.first_name LIKE :first_name OR u.last_name LIKE :last_name OR u.email LIKE :email ORDER BY u.last_name ASC LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':first_name', $filtre_prenom);
    $requete->bindParam(':last_name', $filtre_nom);
    $requete->bindParam(':email', $filtre_email);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nom_prenom_distinct($con){ // Récupération de tous les prénoms et noms distincts des intervenants, cette fonction est utilisée pour afficher la liste des prénoms et noms dans les filtres de recherche de la page de gestion des intervenants pour permettre à l'utilisateur de sélectionner un prénom ou un nom pour filtrer la liste des intervenants
    $requete = $con -> prepare("SELECT DISTINCT first_name, last_name FROM user");
    $requete->execute();
    $infos = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $infos;
}

function select_infos_table_corps_enseignant($con, $offset){ // Récupération de tous les intervenants avec leurs modules enseignés, avec une pagination de 10 intervenants par page, cette fonction est utilisée pour afficher la liste des intervenants avec leurs modules enseignés dans la page de gestion des intervenants
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count, u.id FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id ORDER BY u.last_name ASC LIMIT :offsetend OFFSET :offset");
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function insert_intervention_type($con, $name, $color, $description) { // Insertion d'un nouveau type d'intervention dans la base de données, on doit préciser le nom, la couleur et la description du type d'intervention
    $requete = $con->prepare("INSERT INTO intervention_type (name, description, color) VALUES (:name, :description, :color);");
    $requete->bindParam(':name', $name);
    $requete->bindParam(':color', $color);
    $requete->bindParam(':description', $description);
    $requete->execute();
}

function select_id_intervention_type_where($con, $filtre, $offset) { // Récupération de tous les types d'intervention qui correspondent au filtre de recherche, avec une pagination de 10 types d'intervention par page, cette fonction est utilisée pour afficher la liste des types d'intervention qui correspondent au filtre de recherche dans la page de gestion des types d'intervention
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT id, name, description, color FROM intervention_type WHERE name LIKE :filtre ORDER BY name ASC LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':filtre', $filtre);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_entier($con){ // Récupération du nombre total de modules enseignés par les intervenants, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste des modules enseignés par les intervenants dans la page de gestion des intervenants
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_filtre($con, $filtre_prenom, $filtre_nom, $filtre_email){ // Récupération du nombre total de modules enseignés par les intervenants qui correspondent aux filtres de recherche, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste des modules enseignés par les intervenants qui correspondent aux filtres de recherche dans la page de gestion des intervenants
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id WHERE u.first_name LIKE :first_name OR u.last_name LIKE :last_name OR u.email LIKE :email");
    $requete->bindParam(':first_name', $filtre_prenom);
    $requete->bindParam(':last_name', $filtre_nom);
    $requete->bindParam(':email', $filtre_email);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_filtre_intervention($con, $filtre){ // Récupération du nombre total de types d'intervention qui correspondent au filtre de recherche, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste des types d'intervention qui correspondent au filtre de recherche dans la page de gestion des types d'intervention
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM intervention_type WHERE name=:filtre");
    $requete->bindParam(':filtre', $filtre);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_filtre_intervention_all($con){ // Récupération du nombre total de types d'intervention, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste de tous les types d'intervention dans la page de gestion des types d'intervention
    $requete = $con->prepare("SELECT count(*) AS nblignes FROM intervention_type");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_infos_enseignant($con, $id){ // Récupération des informations d'un intervenant à partir de son id, cette fonction est utilisée pour afficher les informations d'un intervenant dans la fiche de cet intervenant et pour pré-remplir les champs du formulaire de modification de cet intervenant
    $requete = $con->prepare("SELECT email, last_name, first_name FROM user WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $infos = $requete->fetch(PDO::FETCH_ASSOC);
    return $infos;
}


function select_infos_modules_enseignant($con, $id){ // Récupération des informations des modules enseignés par un intervenant à partir de l'id de cet intervenant, cette fonction est utilisée pour afficher les informations des modules enseignés par un intervenant dans la fiche de cet intervenant et pour pré-sélectionner les modules enseignés par cet intervenant dans le formulaire de modification
    $requete = $con->prepare("SELECT m.name, m.hours_count FROM instructor_module im JOIN module m ON im.module_id = m.id  WHERE im.instructor_id= :id ");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_modules_corp_enseignant($con){ // Récupération de tous les modules enseignés par les intervenants, cette fonction est utilisée pour afficher la liste de tous les modules enseignés par les intervenants dans la page de gestion des intervenants
    $requete = $con->prepare("SELECT m.name FROM  module m;");
    $requete->execute();
    $nom_module = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module;
}

function select_modules_enseignées($con, $id){ // Récupération de tous les modules enseignés par un intervenant à partir de l'id de cet intervenant, cette fonction est utilisée pour afficher la liste de tous les modules enseignés par un intervenant dans la fiche de cet intervenant et pour pré-sélectionner les modules enseignés par cet intervenant dans le formulaire de modification
    $requete = $con->prepare("SELECT m.name FROM  module m JOIN instructor_module im ON m.id = im.module_id WHERE im.instructor_id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $nom_module_selected = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $nom_module_selected;
}

function update_infos_enseignant($con, $id, $last_name, $first_name, $email, $name){ // Mise à jour des informations d'un intervenant à partir de son id, on peut mettre à jour le nom, le prénom, l'email et les modules enseignés par cet intervenant, pour mettre à jour les modules enseignés par cet intervenant, on supprime d'abord tous les liens entre cet intervenant et les modules qu'il enseigne dans la table instructor_module, puis on insère les nouveaux liens entre cet intervenant et les modules qu'il enseigne dans la table instructor_module
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

function filtre_fiche_enseignant($con, $id,  $filtre_start_date, $filtre_end_date, $filtre_name, $offset){ // Récupération de tous les cours associés à un intervenant à partir de l'id de cet intervenant et des filtres de recherche, avec une pagination de 10 cours par page, cette fonction est utilisée pour afficher la liste des cours associés à un intervenant dans la fiche de cet intervenant en fonction des filtres de recherche
    $offend = $offset + 10;
    if (!empty($filtre_start_date)) {
        $filtre_start_date = $filtre_start_date->format('Y-m-d H:i:s');
    }
    if (!empty($filtre_end_date)) {
        $filtre_end_date = $filtre_end_date->format('Y-m-d H:i:s');
    }
    $requete = $con->prepare("SELECT c.id, c.start_date, c.end_date, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id WHERE u.id = :id AND ( c.start_date LIKE :inter_start_date OR c.end_date LIKE :inter_end_date OR m.name LIKE :module_name) ORDER BY c.start_date ASC LIMIT :offsetend OFFSET :offset");
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

function fiche_enseignant_tableau_intervenants($con, $element ){ // Récupération de tous les intervenants associés à un cours à partir de l'id de ce cours, cette fonction est utilisée pour afficher la liste des intervenants associés à un cours dans la fiche de ce cours
    $requete = $con->prepare("SELECT upper(u.last_name), upper(u.first_name) FROM user u join instructor i ON i.user_id = u.id join course_instructor ci ON ci.instructor_id = i.id WHERE ci.course_id = :id");
    $requete -> bindParam(':id', $element); 
    $requete->execute();
    $noms_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $noms_intervenants;
}

function fiche_enseignant_tableau($con, $id ,$offset){ // Récupération de tous les cours associés à un intervenant à partir de l'id de cet intervenant, avec une pagination de 10 cours par page, cette fonction est utilisée pour afficher la liste des cours associés à un intervenant dans la fiche de cet intervenant
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT c.id, c.start_date, c.end_date, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id WHERE u.id = :id ORDER BY c.start_date ASC LIMIT :offsetend OFFSET :offset");
    $requete->bindParam(':id', $id);
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}


function select_nb_pages_filtre_fiche_enseignant($con, $id, $filtre_start_date, $filtre_end_date, $filtre_name){ // Récupération du nombre total de cours associés à un intervenant qui correspondent aux filtres de recherche, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste des cours associés à un intervenant dans la fiche de cet intervenant en fonction des filtres de recherche
    if (!empty($filtre_start_date)) {
        $filtre_start_date = $filtre_start_date->format('Y-m-d H:i:s');
    }
    if (!empty($filtre_end_date)) {
        $filtre_end_date = $filtre_end_date->format('Y-m-d H:i:s');
    }
    $requete = $con->prepare("SELECT i.id  FROM instructor i  WHERE i.user_id = :id ");
    $requete->bindParam(':id', $id);
    $requete->execute(); 
    $instructor = $requete->fetch(\PDO::FETCH_ASSOC);
    $instructor = $instructor['id'];

    $requete = $con->prepare("SELECT count(*) AS nblignes FROM course_instructor ci JOIN course c ON ci.course_id = c.id JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id WHERE ci.instructor_id = :id AND ( c.start_date LIKE :inter_start_date OR c.end_date LIKE :inter_end_date OR m.name LIKE :module_name )");
    $requete->bindParam(':id', $instructor);
    $requete->bindParam(':inter_start_date', $filtre_start_date);
    $requete->bindParam(':inter_end_date', $filtre_end_date);
    $requete->bindParam(':module_name', $filtre_name);
    $requete->execute();
    $contenu = $requete->fetch(\PDO::FETCH_ASSOC);
    $contenu = $contenu['nblignes'];
    return $contenu;
}


function select_nb_pages_fiche_enseignant($con, $id){ // Récupération du nombre total de cours associés à un intervenant, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste des cours associés à un intervenant dans la fiche de cet intervenant
    $requete = $con->prepare("SELECT i.id  FROM instructor i  WHERE i.user_id = :id ");
    $requete->bindParam(':id', $id);
    $requete->execute(); 
    $instructor = $requete->fetch(\PDO::FETCH_ASSOC);
    $instructor = $instructor['id'];

    $requete = $con->prepare("SELECT count(*) AS nblignes FROM course_instructor ci WHERE ci.instructor_id = :id");
    $requete->bindParam(':id', $instructor);
    $requete->execute();
    $contenu = $requete->fetch(\PDO::FETCH_ASSOC);
    $contenu = $contenu['nblignes'];
    return $contenu;
}

function select_parent($con) { // Récupération de tous les modules qui n'ont pas de parent, cette fonction est utilisée pour afficher la liste des modules dans le formulaire d'ajout ou de modification d'une intervention pour permettre à l'utilisateur de sélectionner le module associé à l'intervention
    $requete = $con -> prepare("SELECT id, name, hours_count FROM module WHERE parent_id IS NULL;");
    $requete->execute();
    $infos = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $infos;
}

function calendrier_tableau($con, $offset){ // Récupération de tous les cours avec leurs modules associés, avec une pagination de 10 cours par page, cette fonction est utilisée pour afficher la liste de tous les cours avec leurs modules associés dans la page de calendrier
    $offend = $offset + 10;
    $requete = $con->prepare("SELECT DISTINCT c.id, c.start_date, c.end_date, c.title, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id ORDER BY c.start_date ASC LIMIT :offsetend OFFSET :offset");
    $requete->bindValue(':offsetend', (int) $offend, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}
function calendrier_tableau_Count($con){ // Récupération de tous les cours avec leurs modules associés, cette fonction est utilisée pour afficher la liste de tous les cours avec leurs modules associés dans la page de calendrier
    $requete = $con->prepare("SELECT DISTINCT c.id, c.start_date, c.end_date, c.intervention_type_id, m.name AS module, it.name AS type_name, c.remotely FROM course c JOIN module m ON c.module_id = m.id JOIN intervention_type it ON c.intervention_type_id = it.id JOIN course_instructor ci ON c.id = ci.course_id JOIN instructor i ON ci.instructor_id = i.id JOIN user u ON i.user_id = u.id ");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function select_nb_pages_calendrier($con){ // Récupération du nombre total de cours avec leurs modules associés, cette fonction est utilisée pour calculer le nombre de pages nécessaires pour afficher la liste de tous les cours avec leurs modules associés dans la page de calendrier
    $requete = $con->prepare("SELECT DISTINCT count(*) AS nblignes FROM course c ");
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu;
}

function insert_infos_intervention($con, $title, $date_start, $date_end, $module, $typeintervention, $intervenant, $visio){ // Insertion d'un nouveau cours dans la base de données, on doit préciser le titre, la date de début, la date de fin, le module associé, le type d'intervention associé, les intervenants associés et si l'intervention se déroule en visio ou non

    $requete = $con->prepare("SELECT it.id FROM intervention_type it WHERE name = :name ");
    $requete->bindParam(':name', $typeintervention);
    $requete->execute();
    $typeintervention_id = $requete->fetch(PDO::FETCH_ASSOC);
    $typeintervention_id = $typeintervention_id['id'];

    $requete = $con->prepare("SELECT m.id FROM module m WHERE name = :name ");
    $requete->bindParam(':name', $module);
    $requete->execute();
    $module_id = $requete->fetch(PDO::FETCH_ASSOC);
    $module_id = $module_id['id'];

    $course_id = select_nb_pages_calendrier($con);
    $course_id= $course_id[0]["nblignes"];
    $course_id= $course_id +1;

    $requete = $con->prepare("INSERT INTO course (id, start_date, end_date, intervention_type_id, module_id, remotely, title) VALUES (:id, :start_date, :end_date, :intervention_type_id, :module_id, :remotely, :title)");
    $requete->bindParam(':id', $course_id);
    $requete->bindParam(':start_date', $date_start);
    $requete->bindParam(':end_date', $date_end);
    $requete->bindParam(':intervention_type_id', $typeintervention_id);
    $requete->bindParam(':module_id', $module_id);
    $requete->bindParam(':remotely', $visio);
    $requete->bindParam(':title', $title);

    $requete->execute();



    foreach ($intervenant as $id_user) {
        $requete = $con->prepare("SELECT i.id FROM instructor i WHERE user_id = :id_user ");
        $requete->bindParam(':id_user', $id_user);
        $requete->execute();
        $intervenant_id = $requete->fetch(PDO::FETCH_ASSOC)['id'];

        $requete = $con->prepare("INSERT INTO course_instructor (course_id, instructor_id) VALUES (:course_id , :instructor_id)");
        $requete->bindParam('course_id', $course_id);
        $requete->bindParam(':instructor_id', $intervenant_id);
        $requete->execute();
    }
}


function verification_insert_intervention($con, $date_start, $date_end, $module, $intervenant){ // Vérification des informations d'un nouveau cours avant de l'insérer dans la base de données, on vérifie que la date de début est inférieure à la date de fin, que la durée du cours ne dépasse pas 4 heures et que le module associé est enseigné par tous les intervenants associés à ce cours

    $module_verification = 0;

    $requete = $con->prepare("SELECT m.id FROM module m WHERE name = :name ");
    $requete->bindParam(':name', $module);
    $requete->execute();
    $module_id = $requete->fetch(PDO::FETCH_ASSOC)['id'];

    $start = new DateTime("$date_start");
    $end = new DateTime("$date_end");
    $time = $start->diff($end);
    $hours = ($time->days * 24) + $time->h;

    if (($start < $end) && ($hours<= 4) ){
        foreach ($intervenant as $id_user) {
            $requete = $con->prepare("SELECT i.id FROM instructor i WHERE user_id = :id_user ");
            $requete->bindParam(':id_user', $id_user);
            $requete->execute();
            $intervenant_id = $requete->fetch(PDO::FETCH_ASSOC)['id'];

            $requete = $con->prepare("SELECT m.id FROM  module m JOIN instructor_module im ON m.id = im.module_id WHERE im.instructor_id = :intervenant_id");
            $requete->bindParam(':intervenant_id', $intervenant_id);
            $requete->execute();
            $modules_intervenant = $requete->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($modules_intervenant as $module_intervenant) {
                if ($module_intervenant['id'] == $module_id){
                    $module_verification +=1;
                }
            }
        }
        if ( $module_verification == count($intervenant)){
            return True;
        }
        else {
            echo "module non enseigné par un des enseignants";
            return False;
        }
    }
    else{
        echo "horraire incorecte, une intervention dure entre 1 minute et 4 heures";
        return False ;
    }
}
