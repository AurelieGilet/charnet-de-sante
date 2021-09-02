const close = document.querySelectorAll(".close");
const editModalClose = document.getElementsByClassName("edit-modal-close-btn");
const deleteModalClose = document.getElementsByClassName("delete-modal-close-btn");
const addWeightBtn = document.getElementsByClassName("add-weight");
const addWeightForm = document.getElementById("add-weight-form");
const editWeightBtn = document.getElementsByClassName("edit-weight-btn");
const editWeightForm = document.getElementsByClassName("edit-weight-form");
const deleteWeightBtn = document.getElementsByClassName("delete-weight-btn");
const deleteWeightForm = document.getElementsByClassName("delete-weight-form");

if (close != null) {
  for (let i = 0; i < close.length; i++) {
    close[i].addEventListener("click", function () {
      addWeightForm.style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

if (editModalClose != null) {
  for (let i = 0; i < editModalClose.length; i++) {
    editModalClose[i].addEventListener("click", function () {
      editWeightForm[i].style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

if (deleteModalClose != null) {
  for (let i = 0; i < deleteModalClose.length; i++) {
    deleteModalClose[i].addEventListener("click", function () {
      deleteWeightForm[i].style.display = "none";
      body[0].classList.remove("modal-open");
    });
  }
}

for (let i = 0; i < addWeightBtn.length; i++) {
  addWeightBtn[i].addEventListener("click", function () {
    addWeightForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}

for (let i = 0; i < editWeightBtn.length; i++) {
  editWeightBtn[i].addEventListener("click", function () {
    let measureId = editWeightBtn[i].dataset.measureId;
    let editWeightForm = document.getElementById("edit-" + measureId);
    editWeightForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}

for (let i = 0; i < deleteWeightBtn.length; i++) {
  deleteWeightBtn[i].addEventListener("click", function () {
    let measureId = deleteWeightBtn[i].dataset.measureId;
    let deleteWeightForm = document.getElementById("delete-" + measureId);
    deleteWeightForm.style.display = "block";
    body[0].classList.add("modal-open");
  });
}
