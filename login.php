<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Minimalistyczna Strona Logowania</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
    <title>Dynamic Content</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <style>
      :root {
        --bg-color: #181818;
        --secondary-color: #1e1e1e;
        --font-color: #eeeeee;
        --input-color: #23232385;
        --main-colour-font: #b692fd;
      }

      body {
        background-color: var(--bg-color);
        color: var(--font-color);
        font-family: "Roboto", sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
      }

      h2 {
        text-align: center;
      }

      .form-container {
        background-color: var(--secondary-color);
        padding: 10px 20px 20px 20px;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.048);
      }

      .form-group {
        margin-bottom: 10px;
      }

      .form-group label {
        display: block;
        font-size: 0.7rem;
        margin-bottom: 5px;
        color: rgb(169, 169, 169);
      }

      .form-group input {
        width: 90%;
        padding: 6px 10px;
        background-color: rgba(0, 0, 0, 0.095);
        border: 1px solid rgba(255, 255, 255, 0.048);
        border-radius: 5px;
        color: var(--font-color);
      }

      .form-group i {
        margin-right: 5px;
        color: var(--main-colour-font);
      }

      .btn-submit {
        background-color: rgb(24, 24, 24);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.693);
        font-size: 10px;
        padding: 3px 24px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 100;
        margin-left: auto;
        margin-right: auto;
        font-size: 12px;
        display: block;
        transition: 0.3s;
      }
      .btn-submit:hover {
        background-color: rgba(24, 24, 24, 0.577);
      }

      .header {
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      }

      .colour {
        color: var(--main-colour-font);
      }
            a { 
        text-decoration: none;
        color: var(--main-colour-font);
        font-size: 0.6rem;
        text-align: center;
      }
    </style>
  </head>
  <body>


<?php
session_start();

// Konfiguracja
$csrfToken = $_SESSION['csrf_token'] ?? '';
$db = new mysqli('localhost', 'root', '', 'bramki');

if ($db->connect_error) {
    die("Nie udało się połączyć z bazą danych: " . $db->connect_error);
}

// Sprawdzenie, czy użytkownik jest już zalogowany
if (isset($_SESSION['user_id'])) {
    $_SESSION['notification'] = 'Jesteś już zalogowany!';
    header("Location: /bramki/index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdzenie tokena CSRF
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $csrfToken) {
    //     $_SESSION['notification'] = 'Błąd bezpieczeństwa.';
    //     header('Location: /bramki/login.php');
    //     exit;
    // }

    $username = $db->real_escape_string($_POST['username']);
    $password = $_POST['password'];

        // Pobieranie danych użytkownika z bazy danych
    $stmt = $db->prepare("SELECT user_id, password, nickname FROM users WHERE nickname = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['nickname'] = $user['nickname']; // Ustawienie nickname użytkownika
            $_SESSION['notification'] = 'Zalogowano pomyślnie!';
            header("Location: /bramki/index.php");
            exit;
        } else {
            $_SESSION['notification'] = 'Nieprawidłowe hasło.';
            header('Location: /bramki/login.php');
            exit;
        }
    } else {
        $_SESSION['notification'] = 'Nieprawidłowa nazwa użytkownika.';
        header('Location: /bramki/login.php');
        exit;
    }
  }


?>

    <?php include 'php/notification.php'; ?>


    <div class="form-container">
      <div class="header">
        <h2 class="main" style="text-align: center">
cwele<b class="colour">.club</b>
        </h2>
      </div>
   <form method="post" action="/bramki/login.php">
  <div class="form-group">
    <label for="username"><i class="fas fa-user"></i>Nazwa Użytkownika</label>
    <input type="text" id="username" name="username" required />
  </div>

  <div class="form-group">
    <label for="password"><i class="fas fa-lock"></i>Hasło</label>
    <input type="password" id="password" name="password" required />
  </div>

  <button type="submit" class="btn-submit">Zaloguj</button>
  <a href="/bramki/create.php">Nie masz konta? Zarejestruj się!</a>
</form>



    </div>
  </body>
</html>
