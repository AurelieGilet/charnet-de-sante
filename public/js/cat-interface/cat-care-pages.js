// FORMS MODALS

const addEditModalClose = document.getElementsByClassName(
  "add-edit-modal-close-btn"
);
const deleteModalClose = document.getElementsByClassName(
  "delete-modal-close-btn"
);
const addEditDataBtn = document.getElementsByClassName(
  "add-edit-data-btn"
);
const addEditDataForm = document.getElementsByClassName(
  "add-edit-data-form"
);
const deleteDataBtn = document.getElementsByClassName("delete-data-btn");
const deleteDataForm = document.getElementsByClassName(
  "delete-data-form"
);

if (addEditModalClose != null) {
  for (let i = 0; i < addEditModalClose.length; i++) {
    addEditModalClose[i].addEventListener("click", function () {
      addEditDataForm[i].style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

if (deleteModalClose != null) {
  for (let i = 0; i < deleteModalClose.length; i++) {
    deleteModalClose[i].addEventListener("click", function () {
      deleteDataForm[i].style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

for (let i = 0; i < addEditDataBtn.length; i++) {
  addEditDataBtn[i].addEventListener("click", function () {
    let dataId = addEditDataBtn[i].dataset.dataId;
    let addEditDataForm = document.getElementById("edit-" + dataId);
    addEditDataForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}

for (let i = 0; i < deleteDataBtn.length; i++) {
  deleteDataBtn[i].addEventListener("click", function () {
    let dataId = deleteDataBtn[i].dataset.dataId;
    let deleteDataForm = document.getElementById("delete-" + dataId);
    deleteDataForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}

// TOOLTIPS

const helpIcon = document.getElementById("help-icon");
const helpPurpleIcon = document.getElementById("help-purple");
const helpWhiteIcon = document.getElementById("help-white");
const helpContent = document.getElementById("help-content");

helpIcon.addEventListener("click", function () {
  helpIcon.classList.toggle("icon-background-color");
  helpPurpleIcon.classList.toggle("show");
  helpWhiteIcon.classList.toggle("hide");
  helpContent.classList.toggle("hide");
});

window.addEventListener("click", function (event) {
  if (!helpIcon.contains(event.target)) {
    helpIcon.classList.remove("icon-background-color");
    helpPurpleIcon.classList.add("show");
    helpWhiteIcon.classList.add("hide");
    helpContent.classList.add("hide");
  }
});

// NOTES FORM CHARACTERS COUNT

const notesInput = document.getElementsByClassName('notes-input');
const notesCounter = document.getElementsByClassName('notes-counter');

for (let i = 0; i < notesInput.length; i++) {
  notesInput[i].addEventListener('keyup', function() {
    const value = notesInput[i].value;
    
    notesCounter[i].innerHTML = value.length +'/2000';

    if (value.length < 2000) {
      notesCounter[i].style.color = "var(--success-color)";
      notesCounter[i].style.fontWeight = "normal";
    } else {
      notesCounter[i].style.color = "var(--danger-color)";
      notesCounter[i].style.fontWeight = "bold";
    }
  });
}
