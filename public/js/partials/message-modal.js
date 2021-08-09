const modalClose = document.querySelector(".close");
const modalButton = document.querySelector(".modal-button");
const body = document.getElementsByTagName("body");

if (modalClose != null && modalButton != null) {
  body[0].classList.add("modal-open");
  modalClose.addEventListener("click", function () {
    document.querySelector(".wrapper").style.display = "none";
    body[0].classList.remove("modal-open");
  });

  modalButton.addEventListener("click", function () {
    document.querySelector(".wrapper").style.display = "none";
    body[0].classList.remove("modal-open");
  });
}
