export function toDateFormat(datetime) {
    return `${new Date(datetime).getFullYear()}-${
        new Date(datetime).getMonth() + 1
    }-${new Date(datetime).getDate()}`
}

export function toLongDateTimeFormat(datetime) {
    return `${new Date(datetime).toLocaleDateString()} ${new Date(
        datetime
    ).toLocaleTimeString()}`
}

export function toTwentyFourHourFormat(datetime) {
    return `${new Date(datetime).getHours()}:${new Date(datetime).getMinutes()}`
}
