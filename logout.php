<?php
session_start();
session_unset(); // usuń wszystkie zmienne sesji
session_destroy(); // zniszcz sesję

// Na górze Twojego skryptu lub w pliku konfiguracyjnym
$baseUrl = '/bramki/';

// Gdy potrzebujesz przekierować
header("Location: " . $baseUrl . "login.php");

exit;
?>
