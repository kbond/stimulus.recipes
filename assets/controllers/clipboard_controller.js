import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['source'];
    static values = { source: String };

    copy(event) {
        event.preventDefault();

        navigator.clipboard.writeText(
            this.hasSourceValue ? this.sourceValue : this.sourceTarget.innerHTML || this.sourceTarget.value
        );
        // todo we should dispatch an event on success (ie tooltip event listener can listen to it)
    }
}

