// MODALS
const addEditModalClose = document.getElementsByClassName(
  "add-edit-modal-close-btn"
);
const deleteModalClose = document.getElementsByClassName(
  "delete-modal-close-btn"
);
const addEditDataBtn = document.getElementsByClassName("add-edit-data-btn");
const addEditDataForm = document.getElementsByClassName("add-edit-data-form");
const deleteDataBtn = document.getElementsByClassName("delete-data-btn");
const deleteDataForm = document.getElementsByClassName("delete-data-form");

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

// FAQ ARROW FUNCTION
const arrows = document.querySelectorAll(".arrow");
const faqQuestion = document.querySelectorAll(".faq-question");

for (let i = 0; i < faqQuestion.length; i++) {
  faqQuestion[i].addEventListener("click", function () {
    const entryInfo = this.parentElement.parentElement;
    const faqAnswer = this.parentElement.parentElement.getElementsByClassName("faq-answer");
    
    entryInfo.classList.toggle("open");
    faqAnswer[0].classList.toggle("open");
  });
}

window.addEventListener("click", function (event) {
  const entriesInfo = document.querySelectorAll("entry-info");

  for (let i = 0; i < entriesInfo.length; i++) {
    if (!entriesInfo[i].contains(event.target)) {
      entriesInfo[i].classList.remove("open");
      entriesInfo[i]
        .getElementsByClassName("faq-answer")[0]
        .classList.remove("open");
    }
  }
});
