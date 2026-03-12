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

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Poppins:wght@500;600&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    display:flex;
    font-family:'Montserrat', sans-serif;
}


.lateral-menu{
    width:260px;
    height:100vh;
    background:#F1F2F7;
    padding:28px 22px;
    box-sizing:border-box;
    display:flex;
    flex-direction:column;
}


.logo-menu{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:40px;
}

.logo-menu img{
    width:24px;
}

.title-menu{
    line-height:1.2;
}

.title-menu strong{
    font-size:15px;
    font-weight:600;
    color:#082431;
}

.title-menu span{
    font-size:12px;
    color:#082431;
}


.menu-section{
    margin-bottom:28px;
}

.menu-section-title{
    font-family:'Poppins', sans-serif;
    font-size:11px;
    letter-spacing:1px;
    color:#7C8798;
    margin-bottom:12px;
}

.menu-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
    color:#2C416E;
    margin-bottom:6px;
    font-weight:500;
}

.menu-icon{
    width:18px;
    height:18px;
}


.active-item-menu{
    background:#2C416E;
    color:white;
}


.user-menu{
    margin-top:auto;
    display:flex;
    align-items:center;
    gap:10px;
}

.avatar-menu{
    width:38px;
    height:38px;
    border-radius:50%;
}

.name-menu{
    font-size:14px;
    font-weight:600;
    color:#082431;
}

.menu-role{
    font-size:12px;
    color:#8A94A6;
}


.page-content{
    flex:1;
    padding:40px;
}

</style>
</head>

<body>

<div class="lateral-menu">

<div class="logo-menu">
<img src="image/logo1.png">

<div class="title-menu">
<strong>Lycée Saint-Vincent</strong><br>
<span>Enseignement Supérieur</span>
</div>

</div>

<div class="menu-section">

<div class="menu-section-title">MENU</div>

<a href="#" class="menu-item <?php if($active=='calendrier') echo 'active-item-menu'; ?>">
<img src="image/icon1.png" class="menu-icon">
Calendrier
</a>

<a href="#" class="menu-item <?php if($active=='interventions') echo 'active-item-menu'; ?>">
<img src="image/icon2.png" class="menu-icon">
Interventions
</a>

<a href="#" class="menu-item <?php if($active=='enseignants') echo 'active-item-menu'; ?>">
<img src="image/icon3.png" class="menu-icon">
Corps enseignant
</a>

</div>

<div class="menu-section">

<div class="menu-section-title">PARAMÉTRAGE</div>

<a href="#" class="menu-item <?php if($active=='modules') echo 'active-item-menu'; ?>">
<img src="image/icon4.png" class="menu-icon">
Modules
</a>

<a href="#" class="menu-item <?php if($active=='types') echo 'active-item-menu'; ?>">
<img src="image/icon5.png" class="menu-icon">
Types d’intervention
</a>

</div>

<div class="user-menu">

<img src="image/image1.png" class="avatar-menu">

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