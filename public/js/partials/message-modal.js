const modalClose = document.querySelector(".close");
const modalButton = document.querySelector(".modal-button");

if (modalClose != null && modalButton != null) {
  modalClose.addEventListener("click", function () {
    document.querySelector(".wrapper").style.display = "none";
  });

  modalButton.addEventListener("click", function () {
    document.querySelector(".wrapper").style.display = "none";
  });
}
