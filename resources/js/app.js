// resources/js/app.js

import 'alpinejs';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import flatpickr from "flatpickr";


function attachToggle() {
  const toggle = document.getElementById('toggle-sidebar');
  const sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.replaceWith(toggle.cloneNode(true));
    const newToggle = document.getElementById('toggle-sidebar');
    newToggle.addEventListener('click', () => sidebar.classList.toggle('hidden'));
  }
}
 
document.addEventListener('livewire:initialized', attachToggle);
document.addEventListener('livewire:navigated', attachToggle);
