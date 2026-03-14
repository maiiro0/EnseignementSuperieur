<?php 
$active = "calendrier";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Menu Dashboard</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Poppins:wght@500;600&display=swap" rel="stylesheet">

</head>

<body>

<div class="lateral-menu">

<div class="logo-menu">
<img src="assets/logo1.png">

<div class="title-menu">
<strong>Lycée Saint-Vincent</strong><br>
<span>Enseignement Supérieur</span>
</div>

</div>

<div class="menu-section">

<div class="menu-section-title">MENU</div>

<a href="#" class="menu-item <?php if($active=='calendrier') echo 'active-item-menu'; ?>">
<img src="assets/icon1.png" class="menu-icon">
Calendrier
</a>

<a href="#" class="menu-item <?php if($active=='interventions') echo 'active-item-menu'; ?>">
<img src="assets/icon2.png" class="menu-icon">
Interventions
</a>

<a href="#" class="menu-item <?php if($active=='enseignants') echo 'active-item-menu'; ?>">
<img src="assets/icon3.png" class="menu-icon">
Corps enseignant
</a>

</div>

<div class="menu-section">

<div class="menu-section-title">PARAMÉTRAGE</div>

<a href="#" class="menu-item <?php if($active=='modules') echo 'active-item-menu'; ?>">
<img src="assets/icon4.png" class="menu-icon">
Modules
</a>

<a href="#" class="menu-item <?php if($active=='types') echo 'active-item-menu'; ?>">
<img src="assets/icon5.png" class="menu-icon">
Types d’intervention
</a>

</div>

<div class="user-menu">

<img src="assets/image1.png" class="avatar-menu">

<div>
<div class="name-menu">Stella Ribas</div>
<div class="menu-role">Administrateur</div>
</div>

</div>

</div>

<div class="page-content">

<h1>Page exemple</h1>
<p>Contenu de la page ici.</p>

</div>

</body>
</html>