document.addEventListener("DOMContentLoaded", () => {
  function showSection(sectionId) {
    document.querySelectorAll(".content-section").forEach((section) => {
      section.style.display = "none";
    });
    document.querySelector(sectionId).style.display = "block";
  }

  function updateActiveLink(targetId) {
    // Usuń klasę .active-link z wszystkich linków
    document
      .querySelectorAll(".nav-link, .dropdown-content a")
      .forEach((link) => link.classList.remove("active-link"));
  
    // Dodaj klasę .active-link do aktywnego linka i jego nadrzędnych elementów
    const activeLinks = document.querySelectorAll(`[href="${targetId}"]`);
    activeLinks.forEach((link) => {
      link.classList.add("active-link");
      // Dodaj .active-link również do nadrzędnego linka w menu, jeśli istnieje
      let parentDropdown = link.closest('.dropdown');
      if (parentDropdown) {
        parentDropdown.querySelector('.nav-link').classList.add('active-link');
      }
    });
  }
  

  document
    .querySelectorAll(".nav-link, .dropdown-content a")
    .forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const sectionId = link.getAttribute("href");
        showSection(sectionId);
        updateActiveLink(sectionId);
      });
    });

  // Pokaż domyślną sekcję i zaktualizuj aktywny link
  const defaultSectionId = "#bramki";
  showSection(defaultSectionId);
  updateActiveLink(defaultSectionId);

  // Obsługa przełączania menu
  const menuToggle = document.querySelector(".menu-toggle");
  const nav = document.querySelector("nav");

  menuToggle.addEventListener("click", function () {
    nav.classList.toggle("active");
  });
});
const selectElement = document.getElementById('platformSelect');

selectElement.addEventListener('click', function() {
  // Dodanie klasy open przy kliknięciu (otwarcie select)
  this.classList.toggle('open');
});

selectElement.addEventListener('blur', function() {
  // Usunięcie klasy open, gdy select traci focus (zamknięcie select)
  this.classList.remove('open');
});

selectElement.addEventListener('change', function() {
  // Usunięcie klasy open po wyborze opcji
  this.classList.remove('open');
});
