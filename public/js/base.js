// GO TO TOP BUTTON
const topButton = document.getElementById("top-btn");

window.addEventListener("scroll", () => displayOnScroll());
topButton.addEventListener("click", () => scrollToTop());

function displayOnScroll() {
  if (topButton != null) {
    if (
      document.body.scrollTop > 500 ||
      document.documentElement.scrollTop > 500
    ) {
      topButton.style.display = "flex";
    } else {
      topButton.style.display = "none";
    }
  }
}

function scrollToTop() {
  document.body.scrollTop = 0;
  window.scrollTo({ top: 0, behavior: "smooth" });
}