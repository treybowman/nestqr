import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

// Dark mode toggle
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', {
        on: localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggle() {
            this.on = !this.on;
            localStorage.setItem('darkMode', this.on);
            document.documentElement.classList.toggle('dark', this.on);
        },
        init() {
            document.documentElement.classList.toggle('dark', this.on);
        }
    });
});

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
