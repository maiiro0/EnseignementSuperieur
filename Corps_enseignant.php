<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Coprs_Enseignant</title>
</head>
<body>
    <section class="calendar">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Calendrier</p>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Calendrier</h3>
                <div class="button">
                    <button command="show-modal" commandfor="dialog" class="blue-button">Ajouter une nouvelle intervention</button>
                </div>

                <dialog id="dialog">
                    <button commandfor="dialog" command="close" class="invisible-button"><img src="assets/Frame 1041.png" alt="" id="quit"></button>
                    <div class="add-intervention">
                        <img src="assets/Frame.png" alt="">
                        <div>
                            <h3>Ajouter une intervention</h3>
                            <p>Remplissez les informations ci-dessous</p>
                        </div>
                    </div>
</body>
</html>