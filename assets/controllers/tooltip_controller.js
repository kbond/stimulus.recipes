import { Controller } from '@hotwired/stimulus';
import { createPopper } from '@popperjs/core';

export default class extends Controller {
    static targets = ['content'];
    static values = {
        content: String,
        placement: { type: String, default: 'top' },
        offset: { type: Array, default: [0, 8] },
    };

    tooltipTemplate = `
        <div data-tooltip-target="content" class="ui-tooltip" role="tooltip">
            ${this.contentValue}
        </div>`;
    hidden = true;
    popper;

    disconnect() {
        if (this.popper) {
            this.popper.destroy();
        }

        if (this.hasContentTarget) {
            this.contentTarget.remove();
        }
    }

    /**
     * @param hideAfter in ms
     */
    show({params}) {
        if (!this.hasContentTarget) {
            this.element.appendChild(document.createRange().createContextualFragment(this.tooltipTemplate));
        }

        if (!this.popper) {
            this.popper = createPopper(this.element, this.contentTarget, {
                placement: this.placementValue,
                modifiers: [
                    {
                        name: 'offset',
                        options: {
                            offset: this.offsetValue,
                        },
                    },
                ],
            });
        }

        this.hidden = false;
        this.contentTarget.hidden = false;
        this.popper.update();

        if (params.hideAfter) {
            setTimeout(() => {
                this.hide();
            }, params.hideAfter);
        }
    }

    hide() {
        if (this.hasContentTarget) {
            this.contentTarget.hidden = true;
        }

        this.hidden = true;
    }

    toggle() {
        this.hidden ? this.show() : this.hide();
    }
}

