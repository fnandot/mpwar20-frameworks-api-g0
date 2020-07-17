const toastr = require('toastr');

const onLogEntryCreated = function (event) {

    if ('log_entry_created' !== event.type) {
        return event;
    }

    let level = event.level;
    let notificationText = `<strong>+1 ${level.toUpperCase()}</strong> ${event.message}`;
    switch (level) {
        case 'debug':
            toastr.success(notificationText);
            break;
        case 'info':
        case 'notice':
            toastr.info(notificationText);
            break;
        case 'warning':
            toastr.warning(notificationText);
            break;
        case 'error':
        case 'critical':
        case 'alert':
        case 'emergency':
            toastr.error(notificationText);
            break;
    }

    return event;
};

export default onLogEntryCreated;
