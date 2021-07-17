const modalClose = document.querySelector(".close");
const modalButton = document.querySelector(".modal-button");

modalClose.addEventListener("click", function () {
  document.querySelector(".wrapper").style.display = "none";
});

modalButton.addEventListener("click", function () {
  document.querySelector(".wrapper").style.display = "none";
});
