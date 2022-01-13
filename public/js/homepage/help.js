// ARROW DISPLAY

const arrows = document.querySelectorAll(".arrow");
const articleHeader = document.querySelectorAll(".article-header");

for (let i = 0; i < articleHeader.length; i++) {
  articleHeader[i].addEventListener("click", function () {
    const article = this.parentElement;
    const articleContent =
      this.parentElement.getElementsByClassName("article-content");

    article.classList.toggle("open");
    articleContent[0].classList.toggle("open");
  });
}

window.addEventListener("click", function (event) {
  const articles = document.querySelectorAll("article");

  for (let i = 0; i < articles.length; i++) {
    if (!articles[i].contains(event.target)) {
      articles[i].classList.remove("open");
      articles[i]
        .getElementsByClassName("article-content")[0]
        .classList.remove("open");
    }
  }
});
