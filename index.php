<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dynamic Content</title>
    <link rel="stylesheet" href="styles.css" />
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
  </head>
  <body>
<?php
session_start(); // Zawsze na początku, żeby obsługiwać sesję

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    // Nie jest zalogowany, przekierowanie do strony logowania
    header("Location: login.php");
    exit;
}

// Logika wylogowania
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['user_id']);
    unset($_SESSION['nickname']);
    // Można dodać więcej sesji do usunięcia
    session_destroy();
    header("Location: login.php");
    exit;
}

// Tutaj dodajemy inne skrypty PHP, jeśli są potrzebne...
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <!-- Reszta znaczników head -->
</head>


    <div class="main-container">
      <div class="header">
        <!-- Navigation bar -->
       <nav>
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
        <!-- Hamburger Icon -->
    </button>

    <h2 class="main" style="text-align: center">
        cwele<b class="colour">.club</b>
    </h2>

    <ul class="nav-menu">
        <li class="dropdown">
            <a href="#" class="nav-link dropdown-toggle">
                <i class="fa-solid fa-layer-group"></i> Bramki
                <!-- <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span> -->
            </a>
            <div class="dropdown-content">
                <a href="#oferty">Twoje oferty</a>
                <a href="#platform-selection">Stwórz ofertę</a>
                <a href="#">Statystyki ofert</a>
            </div>
        </li>
    </ul>
    
    <!-- Dodanie informacji o użytkowniku na dole paska nawigacji -->
    <div class="user-info">
    

<p>UID: <?php echo $_SESSION['user_id']; ?></p>
<p>Nick: <?php echo isset($_SESSION['nickname']) ? $_SESSION['nickname'] : 'Nieznany użytkownik'; ?></p>

    </div>
</nav>

      </div>
<!-- Przycisk Wyloguj się z ikoną -->
<form action="logout.php" method="post" style="position: fixed; bottom: 20px; right: 20px;">
    <button type="submit" style="background-color: #f44336; color: white; border: none; cursor: pointer; padding: 10px 20px; border-radius: 5px; font-size: 16px;">
        <i class="fas fa-sign-out-alt"></i> Wyloguj się
    </button>
</form>


<?php include 'php/notification.php'; ?>

<div class="section content-section" id="platform-selection">
 <form action="/bramki/submit_offer.php" method="post">
  <div class="form-group">
    <label for="platformSelect">Wybierz platformę:</label>
    <select id="platformSelect" class="platform" name="platform">
      <option value="">Wybierz...</option>
      <option value="Allegro">Allegro</option>
      <option value="Allegro Lokalnie">Allegro Lokalnie</option>
      <option value="OLX">OLX</option>
    </select>
  </div>

  <div class="form-group">
    <label for="name">Nazwa aukcji</label>
    <input type="text" id="name" name="name" required placeholder="Iphone 13 64 GB">
  </div>

  <div class="form-group">
    <label for="cena">Cena aukcji</label>
    <input type="number" id="cena" name="cena" required placeholder="3200">
  </div>

  <div class="form-group">
    <label for="opis">Opis Aukcji</label>
    <input type="text" id="opis" name="opis" required placeholder="Telefon 64 GB, nowy nieuzywany.">
  </div>

  <div class="form-group">
    <label for="zdjecie">Zdjęcie aukcji</label>
    <input type="text" id="zdjecie" name="zdjecie" required placeholder="Link URL do zdjęcia przedmiotu.">
  </div>
<div class="form-group">
  <label for="username_offer">Nazwa autora oferty</label>
  <input type="text" id="username_offer" name="username_offer" required placeholder="Mefedronik2006">
</div>

<div class="form-group">
  <label for="since_when">Od kiedy (Lata)</label>
  <input type="number" id="since_when" name="since_when" required placeholder="3">
</div>

<div class="form-group">
  <label for="from_where">Pochodzenie</label>
  <input type="text" id="from_where" name="from_where" required placeholder="Warszawa, Mokotow">
</div>

  <button type="submit" class="btn">Stwórz</button>
</form>

</div>
      
        





<div id="oferty" class="oferty content-section" style="display: none">
  <h2 style="text-align: center">Oferty</h2>
  <?php
// Połączenie z bazą danych
$conn = new mysqli('localhost', 'root', '', 'bramki');

// Sprawdzanie błędów połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobieranie ofert dla zalogowanego użytkownika
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM offers WHERE created_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Sprawdzenie, czy znaleziono oferty
if ($result->num_rows > 0) {
    // Wyświetlanie danych każdej oferty
    while($row = $result->fetch_assoc()) {
        echo '<div class="oferta">';
        echo '<h3 class="nazwa">'. htmlspecialchars($row["name"]) .'</h3>';
        echo '<p class="typ">'. htmlspecialchars($row["offer_type"]) .'</p>';
        echo '<p class="cena">'. htmlspecialchars($row["price"]) .' zł</p>';
        echo '<p class="created">'. htmlspecialchars($row["created_at"]) .'</p>';
        echo '<div class="buttons">';
        echo '<button class="btn">';
        echo '<i class="fa-solid fa-magnifying-glass"></i>';
        echo '<span>Sprawdź</span>';
        echo '</button>';
        echo '<button class="btn">';
        echo '<i class="fas fa-edit"></i>';
        echo '<span>Edytuj</span>';
        echo '</button>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "Brak ofert.";
}

// Zamknięcie połączenia
$stmt->close();
$conn->close();
?>

 
</div>
    <script src="js/script.js"></script>
  </body>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var dropdownToggle = document.querySelector('.dropdown-toggle');
  dropdownToggle.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    var dropdownContent = this.nextElementSibling;
    dropdownContent.classList.toggle('expanded'); // Toggle the .expanded class on click
  });
});
</script>


</html>
