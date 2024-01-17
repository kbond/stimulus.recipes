import { Controller } from '@hotwired/stimulus';
import { useTransition, useHover } from 'stimulus-use';

export default class extends Controller {
    static values = {
        autoClose: Number,
    };

    static targets = ['timerbar'];
    autoCloseStartedAt = null;
    autoCloseTimeRemaining = null;
    autoCloseTimeout = null;

    connect() {
        useHover(this);
        useTransition(this, {
            leaveActive: 'transition ease-in duration-200',
            leaveFrom: 'opacity-100',
            leaveTo: 'opacity-0',
            transitioned: true,
        });

        if (this.autoCloseValue) {
            this.autoCloseTimeRemaining = this.autoCloseValue;
            this.#startAutoclose();
        }
    }

    close() {
        this.leave();
    }

    mouseEnter() {
        if (!this.autoCloseValue) {
            return;
        }

        // stop the timer & record how much time is remaining
        clearTimeout(this.autoCloseTimeout);
        this.autoCloseTimeRemaining = this.autoCloseTimeRemaining - (Date.now() - this.autoCloseStartedAt);

        if (this.timerbarTarget) {
            // stop the timerbar animation
            this.timerbarTarget.style.width = this.timerbarTarget.offsetWidth + 'px';
        }
    }

    mouseLeave() {
        if (!this.autoCloseValue) {
            return;
        }

        // restart the timer
        this.#startAutoclose();
    }

    #startAutoclose() {
        this.autoCloseStartedAt = Date.now();

        if (this.timerbarTarget) {
            // establish a starting width for css transition
            this.timerbarTarget.style.width = this.timerbarTarget.offsetWidth + 'px';
            this.timerbarTarget.style.transitionDuration = this.autoCloseTimeRemaining + 'ms';
            setTimeout(() => {
                this.timerbarTarget.style.width = 0;
            }, 10);
        }

        this.autoCloseTimeout = setTimeout(() => {
            this.close();
        }, this.autoCloseTimeRemaining);
    }
}
