const close = document.querySelectorAll(".close");
const editPictureBtn = document.getElementById("edit-picture");
const editPictureForm = document.getElementById("edit-picture-form");

if (close != null) {
  for (let i = 0; i < close.length; i++) {
    close[i].addEventListener("click", function () {
      editPictureForm.style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

editPictureBtn.addEventListener("click", function () {
  editPictureForm.style.display = "block";
  body[0].classList.add("modal-open");
});
