let currentIndex = 0;

const track = document.getElementById('logsTrack');

const slides = document.querySelectorAll('.carousel-slide');
const totalSlides = slides.length;

function moveSlide(direction) {
    if (totalSlides === 0) return;

    currentIndex += direction;

    if (currentIndex < 0) {
        currentIndex = totalSlides - 1; // Skok na sam koniec
    } else if (currentIndex >= totalSlides) {
        currentIndex = 0; // Powrót na początek
    }

    let przesuniecie = -(currentIndex * 100);
    track.style.transform = 'translateX(' + przesuniecie + '%)';
}

setInterval(function () {
    moveSlide(1);
}, 4000);
