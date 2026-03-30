<?php
// Début de la session
session_start();
// Vérification de la déconnexion
if (isset($_GET['deconnexion'])) {
    $_SESSION = [];
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', time()-3600, '/');
    header("Location: index.php");
    exit();
}
// Empêcher la mise en cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
// Vérification de l'authentification
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>