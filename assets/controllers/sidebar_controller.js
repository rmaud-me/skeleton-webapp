import { Controller } from '@hotwired/stimulus';
import { onFirstLoad, Sidebar } from '../mazer/js/components/sidebar.js';

export default class extends Controller {
    connect() {
        let sidebarElement = this.element;
        onFirstLoad(sidebarElement);
        new Sidebar(sidebarElement);
    }
}
