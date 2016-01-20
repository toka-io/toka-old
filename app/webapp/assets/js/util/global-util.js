/**
 * Return a timestamp with the format "m/d/yy h:MM:ss TT"
 * @type {Date}
 */
function timestamp(time) {
    if (typeof time === "undefined")
        return moment().format('MMM D, YYYY h:mma');
    else {
        time = moment.utc(time, 'MMM D, YYYY h:mma');

        return moment(time.toDate()).format('MMM D, YYYY h:mma');
    }
}

function timediff() {
    time = moment.utc(time, 'MMM D, YYYY h:mma');
    var endTime = moment.utc(moment().utc().format('MMM D, YYYY h:mm a'), 'MMM D, YYYY h:mma');

    var hourDuration = moment.duration(endTime.diff(time)).asHours();
    var minDuration = moment.duration(endTime.diff(time)).asMinutes();
    var secDuration = moment.duration(endTime.diff(time)).asSeconds();

    if (hourDuration > 6) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma');
    } else if (hourDuration > 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(hourDuration, 10) + " hours ago";
    } else if (hourDuration == 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(hourDuration, 10) + " hour ago";
    } else if (minDuration > 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(minDuration, 10) + " minutes ago";
    } else if (minDuration == 1) {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(minDuration, 10) + " minute ago";
    } else {
        return moment(time.toDate()).format('MMM D, YYYY h:mma') + " || " + parseInt(secDuration, 10) + " seconds ago";
    }
}

function createrandomid(len) {
    var id = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";

    for(var i=0; i < len; i++)
        id += possible.charAt(Math.floor(Math.random() * possible.length));

    return id;
}