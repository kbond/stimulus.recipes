import { Controller } from '@hotwired/stimulus';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

dayjs.extend(utc).extend(timezone);

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static PHP_TO_DAYJS_FORMAT_MAP = {
        Y: 'YYYY',
        y: 'YY',
        F: 'MMMM',
        M: 'MMM',
        m: 'MM',
        n: 'M',
        d: 'DD',
        j: 'D',
        D: 'ddd',
        l: 'dddd',
        N: 'E',
        w: 'e',
        z: 'DDD',
        H: 'HH',
        G: 'H',
        h: 'hh',
        g: 'h',
        i: 'mm',
        s: 'ss',
    };

    static values = {
        format: {type: String, required: true},
        attribute: String,
    }

    connect() {
        let format = this.formatValue
            .split('')
            .map(char => this.constructor.PHP_TO_DAYJS_FORMAT_MAP[char] || char)
            .join('')
        ;
        let output = dayjs
            .utc(this.element.getAttribute('datetime'))
            .tz(dayjs.tz.guess())
            .format(format)
        ;

        if (this.attributeValue) {
            this.element.setAttribute(this.attributeValue, output);

            return;
        }

        this.element.textContent = output;
    }
}
