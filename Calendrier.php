<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        // en cours par Chloé
    </nav>

    <section class="calendar">
        <div class="breadcrumb">
            <img src="image/home.png" alt="">
            <p>></p>
            <p>Calendrier</p>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Calendrier</h3>
                <div class="button">
                    <p>Ajouter une nouvelle intervention</p>
                </div>
            </div>
            <h4>Interventions de la semaine</h4>

            <table class="table">
                <tr class="columns">
                    <td>Dates de l'intervention</td>
                    <td>Modules & titre</td>
                    <td>Type</td>
                    <td>Intervenants</td>
                    <td>En visio</td>
                </tr>

                <?php
                require_once 'Connexion.php';
                ?>


            </table>
        </section>
    </section>
</body>
</html>