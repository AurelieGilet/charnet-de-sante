const addEditModalClose = document.getElementsByClassName("add-edit-modal-close-btn");
const deleteModalClose = document.getElementsByClassName("delete-modal-close-btn");
const addEditMeasureBtn = document.getElementsByClassName("add-edit-measure-btn");
const addEditMeasureForm = document.getElementsByClassName("add-edit-measure-form");
const deleteMeasureBtn = document.getElementsByClassName("delete-measure-btn");
const deleteMeasureForm = document.getElementsByClassName("delete-measure-form");

if (addEditModalClose != null) {
  for (let i = 0; i < addEditModalClose.length; i++) {
    addEditModalClose[i].addEventListener("click", function () {
      addEditMeasureForm[i].style.display = "none";
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

for (let i = 0; i < addEditMeasureBtn.length; i++) {
  addEditMeasureBtn[i].addEventListener("click", function () {
    let measureId = addEditMeasureBtn[i].dataset.measureId;
    let addEditMeasureForm = document.getElementById("edit-" + measureId);
    addEditMeasureForm.style.display = "block";
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
