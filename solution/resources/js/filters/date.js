import moment from "moment";

const isToday = someDate => {
    return moment().isSame(someDate, 'day');
}

const isYesterday = someDate => {
    return moment().subtract(1, 'days').isSame(someDate, 'day');
}

const isCurrentYear = someDate => {
    return someDate.year() === moment().year()
}

export default (value, format = 'full') => {
    if (typeof value === 'string') {
        value = moment(value)
    }

    if (format === "full") {
        return value.format('DD MMM, YYYY [at] hh:mm A')
    }
    let result = ''
    if (isToday(value)) {
        result = 'Today'
    } else if (isYesterday(value)) {
        result = 'Yesterday'
    } else {
        let format = 'ddd, DD MMM'
        if (!isCurrentYear(value)) {
            format = 'ddd, DD MMM YYYY'
        }
        result = value.format(format);
    }

    return result
}
