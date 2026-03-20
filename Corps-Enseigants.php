<?php
$enseignants = [
    [
        "nom" => "Martins-Jacquelot",
        "prenom" => "Jeff",
        "email" => "j.martins@mentalworks.fr",
        "modules" => [
            "Git",
            "Environnement de travail",
            "Environnement de production",
            "Monitorer une base de données + performance"
        ],
        "heures" => "72h"
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Corps enseignant</title>
<link rel="stylesheet" href="enseigants.CSS">
</head>

<body>

<div class="container">

<div class="header">

<h1>Corps enseignant</h1>

<button class="btn-primary">Ajouter un enseignant</button>

</div>

<div class="filters">

<h3>Filtres</h3>

<form method="GET">

<input type="text" name="nom" placeholder="Saisissez le nom de famille">

<input type="text" name="prenom" placeholder="Saisissez le prénom">

<input type="email" name="email" placeholder="Email">

<button class="btn-filter">Filtrer</button>

</form>

</div>

<div class="result">

<p><strong>1 enseignant trouvé</strong></p>

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

<td><?= htmlspecialchars($e["nom"]) ?></td>

<td><?= htmlspecialchars($e["prenom"]) ?></td>

<td><?= implode(", ", $e["modules"]) ?></td>

<td><?= $e["heures"] ?></td>

<td>
<a class="link" href="#">👁 Accéder à la fiche</a>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>