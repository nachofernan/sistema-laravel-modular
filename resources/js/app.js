import './bootstrap';

import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'

// Registrar el plugin Focus ANTES de iniciar Alpine
Alpine.plugin(focus)

// Iniciar Alpine solo si Livewire no lo ha hecho ya
if (!window.Alpine) {
    window.Alpine = Alpine
    Alpine.start()
}