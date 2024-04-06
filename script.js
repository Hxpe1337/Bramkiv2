document.addEventListener("DOMContentLoaded", () => {
  let currentActiveSection = ""; // Zmienna do śledzenia aktualnie aktywnej sekcji

  const showSection = (sectionId) => {
    document.querySelectorAll(".content-section").forEach((section) => {
      section.style.display = "none";
      section.classList.remove("fade-in");
    });
    // Sprawdź, czy sekcja ma zostać otwarta
    if (sectionId && currentActiveSection !== sectionId) {
      const selectedSection = document.querySelector(sectionId);
      if (selectedSection !== null) {
        selectedSection.style.display = "block";
        selectedSection.classList.add("fade-in"); // Dodanie klasy z animacją
      }
      currentActiveSection = sectionId; // Aktualizacja aktualnie aktywnej sekcji
    } else {
      // Jeśli kliknięto ponownie w ten sam link, zamknij sekcję
      currentActiveSection = "";
    }
  };

  const updateActiveLink = (targetId) => {
    // Usuń klasę .active-link z wszystkich linków
    document
      .querySelectorAll(".nav-link, .dropdown-content a")
      .forEach((link) => {
        link.classList.remove("active-link");
      });
    // Jeśli sekcja ma zostać otwarta, dodaj klasę .active-link do odpowiedniego linku
    if (currentActiveSection) {
      document.querySelectorAll(`[href='${targetId}']`).forEach((link) => {
        link.classList.add("active-link");
      });
    }
  };

  document
    .querySelectorAll(".nav-link, .dropdown-content a")
    .forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const sectionId = link.getAttribute("href");
        showSection(sectionId === currentActiveSection ? "" : sectionId);
        updateActiveLink(sectionId);
      });
    });

  // Obsługa przełączania menu
  const menuToggle = document.querySelector(".menu-toggle");
  const nav = document.querySelector("nav");
  menuToggle.addEventListener("click", () => {
    nav.classList.toggle("active");
  });
});
document.addEventListener("DOMContentLoaded", (event) => {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (event) {
    let isFormValid = true;

    // Znajdź wszystkie wymagane pola input w formularzu
    const inputs = form.querySelectorAll("input[required]");

    inputs.forEach((input) => {
      // Sprawdź czy pole jest puste
      if (!input.value) {
        // Zatrzymaj wysyłanie formularza
        event.preventDefault();
        isFormValid = false;
        // Wyświetl powiadomienie
        showNotification(
          `Proszę wypełnić pole: ${input.previousElementSibling.textContent}`
        );
      }
    });

    // Tutaj możesz dodać inne warunki walidacji

    if (isFormValid) {
      // Jeśli formularz jest poprawny, pozwól mu się wysłać
      form.submit();
    }
  });
});
function showNotification(message) {
  var notification = document.createElement("div");
  notification.textContent = message;
  notification.className = "notification";
  document.body.appendChild(notification);

  // Trigger reflow
  notification.offsetHeight;

  // Add class to show the notification
  notification.classList.add("show");

  // Remove the notification after some time
  setTimeout(function () {
    notification.classList.remove("show");
    // Remove the element from the DOM after the animation
    setTimeout(function () {
      notification.remove();
    }, 500); // Adjust this time to match your animation duration
  }, 5000);
}
