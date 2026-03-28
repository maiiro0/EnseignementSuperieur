<?php

function infos_intervention_type($con, $id){
    $requete = $con->prepare("SELECT name, description, color FROM intervention_type WHERE id=:id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);
    return $contenu[0];
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