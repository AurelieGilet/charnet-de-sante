// MOBILE-MENU

const burgerButton = document.getElementById("burger-btn");
const burgerNav = document.querySelector(".burger-nav");

if (burgerButton != null) {
  burgerButton.addEventListener("click", () => {
    animateBurger();
    menuDisplay();
  });

  window.addEventListener("click", function (event) {
    if (!burgerButton.contains(event.target)) {
      burgerNav.classList.remove("burger-nav-show");
      burgerButton.classList.remove("change");
    }
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 768) {
      burgerButton.classList.remove("change");
      burgerNav.classList.remove("burger-nav-show");
    }
  });
}

function animateBurger() {
  burgerButton.classList.toggle("change");
}

function menuDisplay() {
  if (burgerNav.classList.contains("burger-nav-show")) {
    burgerNav.classList.remove("burger-nav-show");
  } else {
    burgerNav.classList.add("burger-nav-show");
  }
}

// DESKTOP-MENU

if (document.querySelector("#desktop-menu")) {
  document
    .querySelector("#desktop-menu")
    .addEventListener("click", function () {
      this.classList.toggle("border-radius");
      this.querySelector(".custom-select").classList.toggle("open");
      this.querySelector(".custom-options").classList.toggle("open");
    });

  window.addEventListener("click", function (event) {
    const select = document.querySelector(".custom-select");
    if (!select.contains(event.target)) {
      select.classList.remove("open");
      document.querySelector("#desktop-menu").classList.remove("border-radius");
      document.querySelector(".custom-options").classList.remove("open");
    }
  });
}
