const close = document.querySelectorAll(".close");
const editUsernameBtn = document.getElementById("edit-username");
const editUsernameForm = document.getElementById("edit-username-form");
const editEmailBtn = document.getElementById("edit-email");
const editEmailForm = document.getElementById("edit-email-form");
const editPasswordBtn = document.getElementById("edit-password");
const editPasswordForm = document.getElementById("edit-password-form");
const editPictureBtn = document.getElementById("edit-picture");
const editPictureForm = document.getElementById("edit-picture-form");
const deletePictureBtn = document.getElementById("delete-picture");
const deletePictureForm = document.getElementById("delete-picture-form");
const deleteUserBtn = document.getElementById("delete-user");
const deleteUserForm = document.getElementById("delete-user-form");

if (close != null) {
  for (let i = 0; i < close.length; i++) {
    close[i].addEventListener("click", function () {
      editUsernameForm.style.display = "none";
      editEmailForm.style.display = "none";
      editPasswordForm.style.display = "none";
      editPictureForm.style.display = "none";
      deletePictureForm.style.display = "none";
      deleteUserForm.style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

editUsernameBtn.addEventListener("click", function () {
  editUsernameForm.style.display = "block";
  body[0].classList.add("modal-open");
});

editEmailBtn.addEventListener("click", function () {
  editEmailForm.style.display = "block";
  body[0].classList.add("modal-open");
});

editPasswordBtn.addEventListener("click", function () {
  editPasswordForm.style.display = "block";
  body[0].classList.add("modal-open");
});

editPictureBtn.addEventListener("click", function () {
  editPictureForm.style.display = "block";
  body[0].classList.add("modal-open");
});

deletePictureBtn.addEventListener("click", function () {
  deletePictureForm.style.display = "block";
  body[0].classList.add("modal-open");
});

deleteUserBtn.addEventListener("click", function () {
  deleteUserForm.style.display = "block";
  body[0].classList.add("modal-open");
});
