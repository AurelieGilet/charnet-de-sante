import Routing from "../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router";
import Routes from "./js/routes.json";
import axios from "axios";

Routing.setRoutingData(Routes);

const CodeGeneratorForm = document.getElementById("code-generator-form");
const codeDisplay = document.getElementById("code-display");
const accessCode = document.getElementById("access-code");


CodeGeneratorForm.addEventListener("submit", function (event) {
  event.preventDefault();
  ajaxRequestCodeGenerator();
});

function ajaxRequestCodeGenerator() {
    let url = Routing.generate("code-generator", { catId: catId});

    axios({
        method: "post",
        url:  url,
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
      .then((response) => {
        accessCode.innerHTML = response.data;
        codeDisplay.style.display = "flex";
      })
      .catch((error) => {
        window.location.href = "/espace-utilisateur/chat/"+ catId;
      });
}
