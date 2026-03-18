<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types Intervention</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
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
    <section class="intervention-type">
        <div class="breadcrumb">
            <img src="assets/home.png" alt="">
            <p>></p>
            <p>Corps enseignant</p>
            <p>></p>
            <p>(var)</p>
            <p>></p>
            <p>Corps enseignant</p>
        </div>

        <section class="titles-page">
            <div class="align">
                <h3>Corps enseignant  (var)</h3>
            </div>
                <h3 class="yellow">Modules enseignés</h3>
                <div class="">
                <?php
                    $requete = $con->prepare("SELECT u.first_name, u.last_name,m.name AS module, m.hours_count FROM instructor i JOIN user u ON i.user_id =u.id JOIN instructor_module im ON im.instructor_id = i.id JOIN module m ON im.module_id = m.id");
                    $requete->execute();
                    $contenu = $requete->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($contenu as $valeurs=>$element) {
                        echo "<span>". $element["last_name"]. "</span>"; 
                        echo"<span>". ":". "</span>";
                        echo"<td>". $element['module']. "</td>";
                        echo"<br>";
                    }
                ?>  
                </div>
                <div></div>
        </section>
        <section>
            <div>
                <a href="">Informations générales</a>
                <a href="">Interventions</a>
            </div>
            <div>
                <h3 class="yellow">Informations générales</h3>
            </div>
            <form action="" method="post">
                <div class="form-align">
                    <div>
                        <label for="title">Titre</label> </br>
                        <input type="text" placeholder="Saisissez un titre sur l'intervention" name="title" id="title"></br>
                    </div>
                    <div>
                        <label for="title">Titre</label> </br>
                        <input type="text" placeholder="Saisissez un titre sur l'intervention" name="title" id="title"></br>
                    </div>
                    <div>
                        <label for="title">Titre</label> </br>
                        <input type="text" placeholder="Saisissez un titre sur l'intervention" name="title" id="title"></br>
                    </div>   
                </div>

                <label for="inter">Intervenant - champ obligatoire</label></br>
                <select name="inter" id="inter">
                        <option value="">Sélectionner des intervenants</option>
                        <?php
                            $requete = $con->prepare("SELECT upper(last_name), first_name FROM user ORDER BY last_name");
                            $requete->execute();
                            $nom_intervenants = $requete->fetchAll(\PDO::FETCH_ASSOC);
                            foreach ($nom_intervenants as $valeurs=>$element) { 
                                echo "<option>". $element["upper(last_name)"]. " ". $element["first_name"] ."</option>";
                            }
                        ?>
                </select></br>
                <div class="select-button">
                    <button type="submit" class="blue-button selection">Confirmer</button>
                </div>
            </form>
        </section>
    </section>
</body>
</html>