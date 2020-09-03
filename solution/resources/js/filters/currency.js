export default (value, useGrouping = true, currency = 'USD') => {
    if (typeof value !== "number") {
        return value;
    }

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        useGrouping: useGrouping
    });
    return formatter.format(value);
}

