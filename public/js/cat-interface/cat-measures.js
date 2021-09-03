const close = document.querySelectorAll(".close");
const editModalClose = document.getElementsByClassName("edit-modal-close-btn");
const deleteModalClose = document.getElementsByClassName("delete-modal-close-btn");
const addMeasureBtn = document.getElementsByClassName("add-measure-btn");
const addMeasureForm = document.getElementById("add-measure-form");
const editMeasureBtn = document.getElementsByClassName("edit-measure-btn");
const editMeasureForm = document.getElementsByClassName("edit-measure-form");
const deleteMeasureBtn = document.getElementsByClassName("delete-measure-btn");
const deleteMeasureForm = document.getElementsByClassName("delete-measure-form");

if (close != null) {
  for (let i = 0; i < close.length; i++) {
    close[i].addEventListener("click", function () {
      addMeasureForm.style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

if (editModalClose != null) {
  for (let i = 0; i < editModalClose.length; i++) {
    editModalClose[i].addEventListener("click", function () {
      editMeasureForm[i].style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

if (deleteModalClose != null) {
  for (let i = 0; i < deleteModalClose.length; i++) {
    deleteModalClose[i].addEventListener("click", function () {
      deleteMeasureForm[i].style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

for (let i = 0; i < addMeasureBtn.length; i++) {
  addMeasureBtn[i].addEventListener("click", function () {
    addMeasureForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}

for (let i = 0; i < editMeasureBtn.length; i++) {
  editMeasureBtn[i].addEventListener("click", function () {
    let measureId = editMeasureBtn[i].dataset.measureId;
    let editMeasureForm = document.getElementById("edit-" + measureId);
    editMeasureForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}

for (let i = 0; i < deleteMeasureBtn.length; i++) {
  deleteMeasureBtn[i].addEventListener("click", function () {
    let measureId = deleteMeasureBtn[i].dataset.measureId;
    let deleteMeasureForm = document.getElementById("delete-" + measureId);
    deleteMeasureForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}
