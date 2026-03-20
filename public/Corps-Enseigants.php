<?php
$enseignants = [
[
"nom" => "Martins-Jacquelot",
"prenom" => "Jeff",
"modules" => "Git, Environnement de travail, Environnement de production, Monitorer une base de données + performance",
"heures" => "72h"
]
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Corps enseignant</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="enseigants.CSS">

</head>

<body>

    <div class="container">

    <div class="top-bar">
        <h1>Corps enseignant</h1>
        <button class="button-primary">
            Ajouter un enseignant
        </button>
    </div>

    <div class="card">
        <h3 class="card-title">Filtres</h3>

        <form class="filters-teachers">
            <input class="input-field" type="text" placeholder="Nom">
            <input class="input-field" type="text" placeholder="Prénom">
            <input class="input-field" type="email" placeholder="Email">

            <button class="button-secondary">
                Filtrer
            </button>
        </form>
    </div>

    <div class="card">
        <div class="result-text">
            1 enseignant trouvé
        </div>

        <table>
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

                <?php foreach($enseignants as $e): ?>

                <tr>
                    <td><?= $e["nom"] ?></td>
                    <td><?= $e["prenom"] ?></td>
                    <td class="table-modules"><?= $e["modules"] ?></td>
                    <td class="table-hours"><?= $e["heures"] ?></td>

                    <td>
                        <a class="link-primary" href="#">
                            👁 Accéder à la fiche
                        </a>
                    </td>
                </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

</body>
</html>