export function fullName(user) {
    return `${user.lastname}, ${user.firstname} ${
        user.middlename ? user.middlename : ''
    }`.trim()
}

export function shortFullName(user) {
    // Lname suffix fname mi.
    return `${user.lastname}${user.suffix ? ' ' + user.suffix + ',' : ','} ${
        user.firstname
    } ${user.middlename ? user.middlename[0] + '.' : ''}`.trim()
}
