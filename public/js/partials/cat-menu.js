// MOBILE CAT MENU

if (document.querySelector("#mobile-cat-menu")) {
  document
    .querySelector("#mobile-cat-menu")
    .addEventListener("click", function () {
      this.classList.toggle("border-radius");
      this.querySelector(".cat-custom-select").classList.toggle("open");
      this.querySelector(".cat-custom-options").classList.toggle("open");
    });

  window.addEventListener("click", function (event) {
    const select = document.querySelector(".cat-custom-select");
    if (!select.contains(event.target)) {
      select.classList.remove("open");
      document
        .querySelector("#mobile-cat-menu")
        .classList.remove("border-radius");
      document.querySelector(".cat-custom-options").classList.remove("open");
    }
  });
}
