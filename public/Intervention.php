<?php
require_once 'Connexion.php';
$dateStart = $_GET['date_start'] ?? '';
$dateEnd = $_GET['date_end'] ?? '';
$moduleId = $_GET['module_id'] ?? '';

$reqModule = $con->prepare("SELECT id, name FROM module ORDER BY name");
$reqModule->execute();
$modules = $reqModule->fetchAll(PDO::FETCH_ASSOC);

$sql = "
    SELECT 
        c.id,
        c.start_date,
        c.end_date,
        c.title,
        c.remotely,
        m.name AS module,
        it.name AS type,
        GROUP_CONCAT(CONCAT(u.first_name, ' ', UPPER(u.last_name)) SEPARATOR ', ') AS instructors
    FROM course c
    JOIN module m ON c.module_id = m.id
    JOIN intervention_type it ON c.intervention_type_id = it.id
    LEFT JOIN course_instructor ci ON ci.course_id = c.id
    LEFT JOIN instructor i ON ci.instructor_id = i.id
    LEFT JOIN user u ON i.user_id = u.id
    WHERE 1 = 1
";

$params = [];

if (!empty($dateStart)) {
    $sql .= " AND c.start_date >= :date_start";
    $params[':date_start'] = $dateStart;
}

if (!empty($dateEnd)) {
    $sql .= " AND c.end_date <= :date_end";
    $params[':date_end'] = $dateEnd;
}

if (!empty($moduleId)) {
    $sql .= " AND c.module_id = :module_id";
    $params[':module_id'] = $moduleId;
}

$sql .= " GROUP BY c.id ORDER BY c.start_date ASC";

$requete = $con->prepare($sql);
$requete->execute($params);
$interventions = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
require_once 'header.php'?>

<body>

<div class="page-shell">
    <aside class="left-menu"></aside>

    <main class="page-content">
        <div class="content-column">
            <div class="breadcrumb">
                <div class="icon-intervention">
                  <img src="assets/Home.png" alt="Accueil">
                </div>
                <span>›</span>
                <span>Interventions</span>
            </div>

            <section class="intervention-page">
                <div class="page-header">
                    <h1>Interventions</h1>
                    <button class="button-primary">Ajouter une nouvelle intervention</button>
                </div>

                <form method="GET" class="filter-form">
                    <h2 class="filter-title">Filtres</h2>

                    <div class="filter-row">
                        <div class="filter-group filter-group-date">
                            <label for="date_start">Date de début</label>
                            <input
                                type="datetime-local"
                                name="date_start"
                                id="date_start"
                                value="<?= htmlspecialchars($dateStart) ?>"
                            >
                        </div>

                        <div class="filter-group filter-group-date">
                            <label for="date_end">Date de fin</label>
                            <input
                                type="datetime-local"
                                name="date_end"
                                id="date_end"
                                value="<?= htmlspecialchars($dateEnd) ?>"
                            >
                        </div>

                        <div class="filter-group filter-group-module">
                            <label for="module_id">Module</label>
                            <select name="module_id" id="module_id">
                                <option value="">Sélectionnez le module</option>
                                <?php foreach ($modules as $module): ?>
                                    <option
                                        value="<?= $module['id'] ?>"
                                        <?= ($moduleId == $module['id']) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($module['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="button-filter">Filtrer</button>
                    </div>
                </form>

                <p class="result-count"><?= count($interventions) ?> intervention(s) trouvée(s)</p>

                <table class="intervention-table">
                    <thead>
                        <tr>
                            <th class="col-date">Date de l'intervention</th>
                            <th class="col-module">Module &amp; titre</th>
                            <th class="col-type">Type</th>
                            <th class="col-instructors">Intervenants</th>
                            <th class="col-visio">En visio</th>
                            <th class="col-action"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($interventions as $intervention): ?>
                            <tr>
                                <td class="col-date">
                                    <?= date('d/m/Y', strtotime($intervention['start_date'])) ?><br>
                                    <?= date('H\hi', strtotime($intervention['start_date'])) ?> à <?= date('H\hi', strtotime($intervention['end_date'])) ?>
                                </td>

                                <td class="col-module">
                                    <div class="module-name"><?= htmlspecialchars($intervention['module']) ?></div>
                                    <div class="course-title"><?= htmlspecialchars($intervention['title']) ?></div>
                                </td>

                                <td class="col-type">
                                    <?= htmlspecialchars($intervention['type']) ?>
                                </td>

                                <td class="col-instructors">
                                    <?= htmlspecialchars($intervention['instructors']) ?>
                                </td>

                                <td class="col-visio">
                                    <?php if ((int)$intervention['remotely'] === 1): ?>
                                        <div class="icon-intervention">
                                          <image src="assets/VisioOn.png">
                                        </div>
                                    <?php else: ?>
                                        <div class="icon-intervention">
                                          <image src="assets/VisioOff.png">
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="col-action">
                                    <div class="action-link">
                                        <div class="icon-intervention">
                                            <img src="assets/Oeil.png">
                                        </div>
                                        <a href="fiche_intervention.php?id=<?= $intervention['id'] ?>">
                                            Accéder à la fiche
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>
</div>

</body>
</html>