import './bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;

// Dark mode toggle — vanilla JS, no Alpine dependency
window.toggleDarkMode = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', isDark);
};

// Photo gallery lightbox
window.lightbox = function(images, startIndex = 0) {
    return {
        images: images,
        current: startIndex,
        open: true,
        next() { this.current = (this.current + 1) % this.images.length; },
        prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; },
        close() { this.open = false; },
    };
};
