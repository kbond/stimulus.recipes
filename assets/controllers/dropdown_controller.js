import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['content']

    toggle() {
        this.contentTarget.hidden = !this.contentTarget.hidden;
    }

    hide(event) {
        if (!this.element.contains(event.target) && !this.contentTarget.hidden) {
            this.contentTarget.hidden = true;
        }
    }
}

