import './bootstrap';

import Alpine from 'alpinejs';

// Avoid booting a second Alpine instance if another bundle (e.g., Filament) already started it.
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
