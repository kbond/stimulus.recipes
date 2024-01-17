import {Controller} from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ['timerbar']
    static values = {
        autoClose: Number, // auto close delay in ms
    }

    cancelled = false

    connect() {
        if (this.hasTimerbarTarget) {
            this.timerbarTarget.hidden = true;
        }

        if (this.hasAutoCloseValue) {
            this.#remove(this.autoCloseValue);
        }
    }

    /**
     * @param delay in ms
     */
    close({ params }) {
        this.#remove(params.delay || 0);
    }

    /**
     * Cancel the close action if delayed.
     */
    cancel() {
        this.cancelled = true;

        if (this.hasTimerbarTarget) {
            this.timerbarTarget.hidden = true;
        }
    }

    #remove(delay = 0) {
        this.cancelled = false;

        if (this.hasTimerbarTarget && delay) {
            this.timerbarTarget.hidden = false;
            // establish a starting width for css transition
            this.timerbarTarget.style.width = this.timerbarTarget.offsetWidth + 'px';
            this.timerbarTarget.style.transitionDuration = delay + 'ms';
            setTimeout(() => {
                this.timerbarTarget.style.width = 0;
            }, 10);
        }

        setTimeout(() => {
            if (!this.cancelled) {
                this.element.remove();
            }
        }, delay);
    }
}
