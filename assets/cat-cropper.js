import Cropper from "cropperjs/dist/cropper";
import Routing from "../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router";
import Routes from "./js/routes.json";
import axios from "axios";

Routing.setRoutingData(Routes);

let cropper;
const fileInput = document.getElementById("edit_cat_picture_form_picture");
const previewPicture = document.getElementById("preview-picture");
const form = document.getElementById("picture-form");

if (fileInput) {
  fileInput.addEventListener("change", () => {
    previewFile();
  });
}

function previewFile() {
  let file = fileInput.files[0];

  if (file) {
    let reader = new FileReader();

    reader.addEventListener(
      "load",
      function (event) {
        previewPicture.src = reader.result;
      },
      false
    );

    reader.readAsDataURL(file);
  }
}

if (previewPicture) {
  previewPicture.addEventListener("load", function (event) {
    if (cropper) {
      cropper.destroy();
    }
    cropper = new Cropper(previewPicture, {
      aspectRatio: 1 / 1,
    });
  });
}

if (form) {
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    cropper
      .getCroppedCanvas({
        maxHeight: 500,
        maxWidth: 500,
      })
      .toBlob(
        function (blob) {
          ajaxRequestEditPicture(blob);
        },
        "image/jpeg",
        0.95
      );
  });
}

function ajaxRequestEditPicture(blob) {
  // The catId variable is defined directly in a script in the template (=>cat_detail.html.twig).
  let url = Routing.generate("edit-cat-picture", { id: catId });
  let formData = new FormData(form);
  formData.append("file", blob);
  
  axios({
    method: "post",
    url: url,
    data: formData,
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => {
      window.location.href = "/espace-utilisateur/chat/"+ catId;
    })
    .catch((error) => {
      window.location.href = "/espace-utilisateur/chat/"+ catId;
    });
}
