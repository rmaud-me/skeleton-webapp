/**
 * All flatpickr options are not handle by this controller.
 * If you need more options customizable by data-* properties, feel free to do it.
 *
 * To use it read .docs/front-components.md#datepicker
 */
import {Controller} from '@hotwired/stimulus';
import flatpickr from "flatpickr";
import "../styles/datapicker.css";

export default class extends Controller {
    initialize() {
        this.flatpickr = null;
    }

    connect() {
        const dateTimeInputElement = this.element;
        const enableTime = (dateTimeInputElement.dataset.enableTime === 'true');
        const enableTimePickerOnly = (dateTimeInputElement.dataset.enableTimePickerOnly === 'true');
        const maxDate = dateTimeInputElement.dataset.maxDate;

        this.flatpickr = flatpickr(dateTimeInputElement, {
            altInput: true,
            altFormat: this.getAltFormatFromOptions(enableTime, enableTimePickerOnly),
            enableTime: enableTimePickerOnly ? true : enableTime,
            dateFormat: enableTimePickerOnly ? 'H:i' : 'Y-m-d H:i:S',
            noCalendar: enableTimePickerOnly,
            time_24hr: true,
            maxDate: typeof maxDate == 'undefined' ? null : maxDate
        });
    }

    disconnect() {
        this.flatpickr.destroy();
    }

    getAltFormatFromOptions(enableTime, enableTimePickerOnly) {
        if (enableTimePickerOnly) {
            return 'H:i';
        }

        if (enableTime) {
            return 'Y-m-d H:i';
        }

        return 'Y-m-d'
    }
}
