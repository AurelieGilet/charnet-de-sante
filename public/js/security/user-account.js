const close = document.querySelectorAll(".close");
const editUsernameBtn = document.getElementById("edit-username");
const editUsernameForm = document.getElementById("edit-username-form");
const editEmailBtn = document.getElementById("edit-email");
const editEmailForm = document.getElementById("edit-email-form");
const editPasswordBtn = document.getElementById("edit-password");
const editPasswordForm = document.getElementById("edit-password-form");

if (close != null) {
  for (let i = 0; i < close.length; i++) {
    close[i].addEventListener("click", function () {
      editUsernameForm.style.display = "none";
      editEmailForm.style.display = "none";
      editPasswordForm.style.display = "none";
    });
  }
}

editUsernameBtn.addEventListener("click", function () {
  editUsernameForm.style.display = "block";
});

editEmailBtn.addEventListener("click", function () {
  editEmailForm.style.display = "block";
});

editPasswordBtn.addEventListener("click", function () {
  editPasswordForm.style.display = "block";
});
