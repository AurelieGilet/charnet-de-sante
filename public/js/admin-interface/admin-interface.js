// MODALS

const editModalClose = document.getElementsByClassName("edit-modal-close-btn");
const deleteModalClose = document.getElementsByClassName(
  "delete-modal-close-btn"
);
const editDataBtn = document.getElementsByClassName("edit-data-btn");
const editDataForm = document.getElementsByClassName("edit-data-form");
const deleteDataBtn = document.getElementsByClassName("delete-data-btn");
const deleteDataForm = document.getElementsByClassName("delete-data-form");

if (editModalClose != null) {
  for (let i = 0; i < editModalClose.length; i++) {
    editModalClose[i].addEventListener("click", function () {
      editDataForm[i].style.display = "none";
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

for (let i = 0; i < editDataBtn.length; i++) {
  editDataBtn[i].addEventListener("click", function () {
    let dataId = editDataBtn[i].dataset.dataId;
    let editDataForm = document.getElementById("edit-" + dataId);
    editDataForm.style.display = "block";
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
