import './bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;

// Register Alpine store via Livewire's bundled Alpine (do NOT import Alpine separately)
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
