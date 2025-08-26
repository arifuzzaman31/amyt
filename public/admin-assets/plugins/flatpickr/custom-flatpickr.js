// Flatpickr
$(function () {
    // Basic Date Picker
    if ($('#basicFlatpickr').length) {
        var f1 = flatpickr("#basicFlatpickr");
    }

    // Date & Time Picker
    if ($('#dateTimeFlatpickr').length) {
        var f2 = flatpickr("#dateTimeFlatpickr", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    }

    // Range Calendar
    if ($('#rangeCalendarFlatpickr').length) {
        var f3 = flatpickr("#rangeCalendarFlatpickr", {
            mode: "range",
        });
    }

    // Time Picker
    if ($('#timeFlatpickr').length) {
        var f4 = flatpickr("#timeFlatpickr", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "13:45"
        });
    }
});