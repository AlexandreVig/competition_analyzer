// "use strict";
// Select all slides
const slides = document.querySelectorAll("[slide]");

// loop through slides and set each slides translateX
slides.forEach((slide, indx) => {
  slide.style.transform = `translateX(${indx * 100}%)`;
});

function go_to_slide(n) {
  //   move slide by 100%
  slides.forEach((slide, indx) => {
    slide.style.transform = `translateX(${100 * (indx - n)}%)`;
  });
}

// select next slide button
const editionSlide = document.querySelector("[btn-edition]");

// current slide counter
let curSlide = 0;
// maximum number of slides
let maxSlide = slides.length - 1;

// add event listener and navigation functionality
editionSlide.addEventListener("click", function () {
  go_to_slide(1);
});

// select next slide button
const creationSlide = document.querySelector("[btn-creation]");

// add event listener and navigation functionality
creationSlide.addEventListener("click", function () {
  go_to_slide(2);
});

const graphSlide = document.querySelector("[btn-graph]");

// add event listener and navigation functionality
graphSlide.addEventListener("click", function () {
  go_to_slide(0);
});