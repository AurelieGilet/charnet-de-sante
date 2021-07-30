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
