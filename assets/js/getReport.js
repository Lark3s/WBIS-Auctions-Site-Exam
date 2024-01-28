function getYearlyReport() {
    let year = document.getElementById('input-year').value;
    window.location.href = BASE + 'user/profile/analytics/report/year/' + year;
}

function getQuarterlyReport() {
    let year = document.getElementById('input-yearQ').value;
    let quarter = document.getElementById('input-quarter').value;
    window.location.href = BASE + 'user/profile/analytics/report/year/' + year + '/quarter/' + quarter;
}

function getMonthlyReport() {
    let year = document.getElementById('input-yearM').value;
    let month = document.getElementById('input-month').value;
    window.location.href = BASE + 'user/profile/analytics/report/year/' + year + '/month/' + month;
}

function getWeeklyReport() {
    let year = document.getElementById('input-yearW').value;
    let week = document.getElementById('input-week').value;
    window.location.href = BASE + 'user/profile/analytics/report/year/' + year + '/week/' + week;
}
