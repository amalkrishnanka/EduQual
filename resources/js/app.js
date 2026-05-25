import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Make Alpine available globally
window.Alpine = Alpine;
Alpine.start();

// Make Chart.js available globally for Blade views
window.Chart = Chart;
