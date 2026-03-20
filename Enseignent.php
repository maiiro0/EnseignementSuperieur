<?php
$teachers = [
    [
        "lastname" => "Martins-Jacquelot",
        "firstname" => "Jeff",
        "modules" => "Git, Environnement de travail, Environnement de production, Monitorer une base de données + performance",
        "hours" => "72h"
    ]
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Corps enseignant</title>

<link rel="stylesheet" href="Enseignant.CSS">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="teachers-page">

    <div class="page-header">

        <h1 class="page-title">Corps enseignant</h1>

        <button class="add-teacher">
            Ajouter un enseignant
        </button>

    </div>

    <div class="filter-area">

        <p class="filter-title">Filtres</p>

        <form method="GET">

            <div class="filter-grid">

                <div class="filter-group">
                    <label>Nom de famille</label>
                    <input type="text" placeholder="Saisissez le nom de famille">
                </div>

                <div class="filter-group">
                    <label>Prénom</label>
                    <input type="text" placeholder="Saisissez le prénom">
                </div>

                <div class="filter-group">
                    <label>Email</label>
                    <input type="text" placeholder="j.martins@mentalworks.fr">
                </div>

                <button class="filter-btn">
                    Filtrer
                </button>

            </div>

        </form>

    </div>


    <div class="teachers-list">

        <p class="teacher-count">
            <?php echo count($teachers); ?> enseignant trouvé
        </p>

        <table class="teachers-table">

            <thead>
                <tr>
                    <th>Nom de famille</th>
                    <th>Prénom</th>
                    <th>Modules enseignés</th>
                    <th>Nombre d'heures</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($teachers as $teacher) { ?>

                <tr>

                    <td><?php echo $teacher["lastname"]; ?></td>
                    <td><?php echo $teacher["firstname"]; ?></td>
                    <td><?php echo $teacher["modules"]; ?></td>
                    <td><?php echo $teacher["hours"]; ?></td>

                    <td class="teacher-link">
                        <span class="view-icon">👁</span>
                        <a href="#">Accéder à la fiche</a>
                 </td>
                </tr>
            <?php } ?>
           </tbody>
        </table>
    </div>
</div>

</body>
</html>