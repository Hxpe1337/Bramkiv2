<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    $_SESSION['notification'] = "Musisz być zalogowany, aby utworzyć ofertę.";
    header("Location: login.php");
    exit;
}

// Dane połączeniowe do bazy danych
$host = 'localhost';
$dbname = 'bramki';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $platform = trim($_POST['platform']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Walidacja
        $errors = [];
        $platform = trim($_POST['platform']);
        $name = trim($_POST['name']);
        $cena = trim($_POST['cena']);
        $opis = trim($_POST['opis']);
        $zdjecie = trim($_POST['zdjecie']);
        $username_offer = trim($_POST['username_offer']);
        $since_when = trim($_POST['since_when']);
        $from_where = trim($_POST['from_where']);

        // Sprawdzenie, czy pola nie są puste
        if (empty($platform)) {
            $errors['platform'] = 'Proszę wybrać platformę.';
        }
        if (empty($name)) {
            $errors['name'] = 'Proszę wprowadzić nazwę aukcji.';
        }
        if (!is_numeric($cena) || $cena <= 0) {
            $errors['cena'] = 'Proszę wprowadzić prawidłową cenę.';
        }

        // // Walidacja danych (prosta)
        // if (empty($name) || empty($price) || empty($description) || empty($photo) || empty($offer_type) || empty($username_offer) || empty($since_when) || empty($from_where)) {
        //     $_SESSION['notification'] = "Wszystkie pola muszą być wypełnione.";
        //     header("Location: /bramki/index.php");        exit();

        //     exit;
        // }

        // Przygotowanie zapytania SQL
        $sql = "INSERT INTO offers (name, price, created_by, photo, description, revenue, offer_type, username_offer, since_when, from_where) VALUES (?, ?, ?, ?, ?, 0.00, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $created_by = $_SESSION['user_id']; // Pobierz z sesji ID zalogowanego użytkownika
        $offer_type = $platform; // Zmienna `$offer_type` może być równa wybranej platformie
        $stmt->execute([$name, $cena, $created_by, $zdjecie, $opis, $offer_type, $username_offer, $since_when, $from_where]);

        $_SESSION['notification'] = "Oferta została pomyślnie dodana.";
        header("Location: /bramki/index.php");        exit();
    }
} catch (PDOException $e) {
    $_SESSION['notification'] = "Błąd połączenia z bazą danych: " . $e->getMessage();
    header("Location: /bramki/index.php");    exit;
}
?>
