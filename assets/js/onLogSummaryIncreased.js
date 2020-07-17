require('jquery-ui/ui/effect');
require('jquery-ui/ui/effects/effect-highlight');

const moment = require('moment');

const onLogSummaryIncreased = function (event) {

    if ('log_summary_increased' !== event.type) {
        return event;
    }

    let object = $(`[data-id="${event.id}"]`);

    let counterObject = $(`.counter .counter-value`, object);
    let dateTimeObject = $(`.datetime`, object);
    let currentCount = parseInt(counterObject.text());
    let increasedBy = parseInt(event.increased_by);

    counterObject.text(currentCount + increasedBy);
    let icon = $(`.counter .ui-icon`, object);
    icon.show();
    counterObject.effect("highlight", {color: "#ff0"}, 1500, () => {
        icon.hide()
    });

    let formattedDateTime = moment(event.occurred_on * 1000).format('YYYY/MM/DD hh:mm A');

    if (dateTimeObject.text() !== formattedDateTime) {
        dateTimeObject.text(formattedDateTime);
        dateTimeObject.effect("highlight", {color: "#ff0"}, 500);
    }

    return event;
};

export default onLogSummaryIncreased;
