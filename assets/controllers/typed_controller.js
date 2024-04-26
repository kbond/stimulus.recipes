import { Controller } from '@hotwired/stimulus';
import Typed from 'typed.js';

export default class extends Controller {

    static values = {
        strings: Array,
        typeSpeed: { type: Number, default: 30 },
        smartBackspace: { type: Boolean, default: true },
        startDelay: Number,
        backSpeed: Number,
        shuffle: Boolean,
        backDelay: { type: Number, default: 700 },
        fadeOut: Boolean,
        fadeOutClass: { type: String, default: 'typed-fade-out' },
        fadeOutDelay: { type: Number, default: 500 },
        loop: Boolean,
        loopCount: { type: Number, default: Infinity },
        showCursor: { type: Boolean, default: true },
        cursorChar: { type: String, default: '.' },
        autoInsertCss: { type: Boolean, default: true },
        attr: String,
        bindInputFocusEvents: Boolean,
        contentType: { type: String, default: 'html' },
    };

    typed;

    connect() {
        console.log(this.stringsValue);

        this.typed = new Typed(this.element, {
            strings: this.stringsValue,
            typeSpeed: this.typeSpeedValue,
            smartBackspace: this.smartBackspaceValue,
            startDelay: this.startDelayValue,
            backSpeed: this.backSpeedValue,
            shuffle: this.shuffleValue,
            backDelay: this.backDelayValue,
            fadeOut: this.fadeOutValue,
            fadeOutClass: this.fadeOutClassValue,
            fadeOutDelay: this.fadeOutDelayValue,
            loop: this.loopValue,
            loopCount: this.loopCountValue,
            showCursor: this.showCursorValue,
            cursorChar: this.cursorCharValue,
            autoInsertCss: this.autoInsertCssValue,
            attr: this.attrValue,
            bindInputFocusEvents: this.bindInputFocusEventsValue,
            contentType: this.contentTypeValue,
        });
    }

    disconnect() {
        if (this.typed) {
            this.typed.destroy();
        }
    }
}

