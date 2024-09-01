document.addEventListener("DOMContentLoaded", function() {
  var slides = document.getElementsByClassName("slide");
  var currentSlide = 0;

  function showSlide(n) {
    // Скрыть все слайды
    for (var i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }

    // Показать текущий слайд
    slides[n].style.display = "block";
  }

  function nextSlide() {
    if (currentSlide < slides.length - 1) {
      currentSlide++;
    } else {
      currentSlide = 0;
    }
    showSlide(currentSlide);
  }

  function prevSlide() {
    if (currentSlide > 0) {
      currentSlide--;
    } else {
      currentSlide = slides.length - 1;
    }
    showSlide(currentSlide);
  }

  document.getElementById("nextButton").addEventListener("click", nextSlide);
  document.getElementById("prevButton").addEventListener("click", prevSlide);

  // Показать первый слайд при загрузке страницы
  showSlide(currentSlide);
});