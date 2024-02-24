import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dialog']

    open() {
        this.dialogTarget.showModal();
        this.dialogTarget.addEventListener(
            'click',
            (e) => e.target === this.dialogTarget && this.close()
        );
    }

    close() {
        this.dialogTarget.close();
    }
}

