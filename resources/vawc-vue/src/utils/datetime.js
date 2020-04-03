export function toDate(datetime) {
    return `${new Date(datetime).getFullYear()}-${new Date(
        datetime
    ).getMonth() + 1}-${new Date(datetime).getDate()}`
}

export function longDateTime(datetime) {
    return `${new Date(datetime).toLocaleDateString()} ${new Date(
        datetime
    ).toLocaleTimeString()}`
}

export function militaryTime(datetime) {
    return `${new Date(datetime).getHours()}:${new Date(datetime).getMinutes()}`
}
