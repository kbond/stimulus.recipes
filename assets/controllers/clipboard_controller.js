import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['source'];

    copy(event) {
        event.preventDefault();
        navigator.clipboard.writeText(this.sourceTarget.innerHTML || this.sourceTarget.value);
        // todo we should dispatch an event on success (ie tooltip event listener can listen to it)
    }
}

